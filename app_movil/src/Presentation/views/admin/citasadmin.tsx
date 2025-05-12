import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ActivityIndicator, FlatList, Modal, TextInput, Alert, ScrollView } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { Calendar, LocaleConfig } from 'react-native-calendars';
import { useAuth } from '../../components/context/AuthContext';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { FontAwesome } from '@expo/vector-icons';


LocaleConfig.locales['es'] = {
  monthNames: [
    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
  ],
  monthNamesShort: [
    'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
    'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'
  ],
  dayNames: [
    'Domingo', 'Lunes', 'Martes', 'Miércoles',
    'Jueves', 'Viernes', 'Sábado'
  ],
  dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
  today: 'Hoy'
};
LocaleConfig.defaultLocale = 'es';

type EstadoCita = 'pendiente' | 'respondida' | '';

interface Cita {
  idcita: number;
  fechacita: string;
  horacita: string;
  tipocita: string;
  apa: string;
  respuesta: string;
  estado: EstadoCita;
}

const Citasadmin: React.FC<{ navigation: StackNavigationProp<RootStackParamList, 'Citas'> }> = ({ navigation }) => {
  const [citas, setCitas] = useState<Cita[]>([]);
  const { user } = useAuth();
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [modalVisible, setModalVisible] = useState(false);
  const [selectedCita, setSelectedCita] = useState<Cita | null>(null);
  const [respuesta, setRespuesta] = useState('');
  const [calendarVisible, setCalendarVisible] = useState(false);
  const [markedDates, setMarkedDates] = useState({});
  const [selectedDate, setSelectedDate] = useState<string | null>(null);


  const getCitaStyle = (estado: EstadoCita) => {
    switch (estado) {
      case 'pendiente':
        return {
          borderLeftColor: '#FFC107',
          backgroundColor: '#FFF8E1'
        };
      case 'respondida':
        return {
          borderLeftColor: '#061f03',
          backgroundColor: '#E8F5E9'
        };
      default:
        return {
          borderLeftColor: '#8c160e',
          backgroundColor: '#FAFAFA'
        };
    }
  };

  useEffect(() => {
    fetchCitas();
  }, []);

  const fetchCitas = async () => {
    try {
      const response = await fetch('http://192.168.1.105:3001/api/citas');
      if (!response.ok) throw new Error('Error al obtener citas');

      const data = await response.json();
      const parsedData = data.map((item: any) => ({
        idcita: Number(item.idcita),
        fechacita: item.fechacita,
        horacita: item.horacita,
        tipocita: item.tipocita,
        apa: item.apa,
        respuesta: item.respuesta,
        estado: item.estado === 'pendiente' || item.estado === 'respondida' ? item.estado : ''
      }));

      setCitas(parsedData);
      prepareCalendarData(parsedData);
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Error desconocido');
    } finally {
      setLoading(false);
    }
  };

  const prepareCalendarData = (citasData: Cita[]) => {
    const dates: { [key: string]: any } = {};

    citasData.forEach(cita => {

      const dateObj = new Date(cita.fechacita);
      if (isNaN(dateObj.getTime())) return;

      const dateString = dateObj.toISOString().split('T')[0];

      if (!dates[dateString]) {
        dates[dateString] = {
          selected: dateString === selectedDate,
          selectedColor: '#0b4705',
          marked: true,
          dotColor: cita.estado === 'pendiente' ? '#FFC107' :
            cita.estado === 'respondida' ? '#8c160e' : '#9E9E9E'
        };
      } else {

        if (cita.estado === 'pendiente') {
          dates[dateString].dotColor = '#FFC107';
        } else if (cita.estado === 'respondida' && dates[dateString].dotColor !== '#FFC107') {
          dates[dateString].dotColor = '#4CAF50';
        }
      }
    });

    setMarkedDates(dates);
  };


  const handleDayPress = (day: any) => {
    setSelectedDate(day.dateString);
    prepareCalendarData(citas);
  };
  const filteredCitas = selectedDate
    ? citas.filter(cita => {
      const citaDate = new Date(cita.fechacita).toISOString().split('T')[0];
      return citaDate === selectedDate;
    })
    : citas;

  const handleResponder = async () => {
    if (!selectedCita || !respuesta.trim()) {
      Alert.alert('Error', 'Por favor escribe una respuesta');
      return;
    }

    try {
      const response = await fetch('http://192.168.1.105:3001/api/citas/responder', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          idcita: selectedCita.idcita,
          respuesta: respuesta
        }),
      });

      if (!response.ok) throw new Error('Error al enviar respuesta');

      await fetchCitas();
      setModalVisible(false);
      setRespuesta('');
      Alert.alert('Éxito', 'Respuesta enviada correctamente');
    } catch (err) {
      Alert.alert('Error', err instanceof Error ? err.message : 'Error desconocido');
    }
  };

  const renderCitaItem = ({ item }: { item: Cita }) => {
    const citaStyle = getCitaStyle(item.estado);

    return (
      <View style={[styles.citaItem, citaStyle]}>
        <View style={styles.citaHeader}>
          <View style={styles.citaTypeBadge}>
            <Text style={styles.citaTypeText}>{item.tipocita}</Text>
          </View>
          <Text style={styles.citaTime}>
            {item.horacita}
          </Text>
        </View>

        <View style={styles.citaDetails}>
          <Text style={styles.citaApartamento}>Apartamento: {item.apa}</Text>
          <View style={styles.citaStatusContainer}>
            <Text style={[
              styles.citaEstado,
              item.estado === 'pendiente' ? styles.estadoPendiente :
                item.estado === 'respondida' ? styles.estadoRespondida : styles.estadoDefault
            ]}>
              {item.estado.toUpperCase()}
            </Text>
          </View>
        </View>

        {item.respuesta && (
          <View style={styles.respuestaContainer}>
            <Text style={styles.respuestaLabel}>Respuesta:</Text>
            <Text style={styles.citaRespuesta}>{item.respuesta}</Text>
          </View>
        )}

        <View style={styles.actionsContainer}>
          {item.estado === 'pendiente' && (
            <TouchableOpacity
              style={[styles.actionButton, styles.responderButton]}
              onPress={() => {
                setSelectedCita(item);
                setModalVisible(true);
              }}
            >
              <Ionicons name="create-outline" size={18} color="#FFF" />
              <Text style={styles.buttonText}> Responder</Text>
            </TouchableOpacity>
          )}

          <TouchableOpacity
            style={[styles.actionButton, styles.deleteButton]}

          >
            <Ionicons name="trash-outline" size={18} color="#FFF" />
            <Text style={styles.buttonText}> Eliminar</Text>
          </TouchableOpacity>
        </View>
      </View>
    );
  };



  return (

    <View style={styles.container}>

      <View style={styles.header}>

      </View>
      <View style={styles.header}>
        <View style={styles.userInfo}>
          <Image
            source={require('./img/ajustes.png')}
            style={styles.logo}
          />
          <View style={styles.welcomeContainer}>
            <Text style={styles.userName}>Admin</Text>
            <Text style={styles.welcomeText}>
              {user ? `${user.Usuario} ` : 'Usuario'}
            </Text>
            
          </View>
        </View>

      </View>

      <View style={styles.header}>
        <View style={styles.header}>
          <View style={styles.headerContent}>
            <TouchableOpacity onPress={() => navigation.goBack()}>
            <FontAwesome name="arrow-left" size={24} color="#0b4705" />
            </TouchableOpacity>


            <View style={styles.headerTitleContainer}>

              <Text style={styles.headerTitle}>Control de Citas</Text>
              <Text style={styles.headerSubtitle}>
                {selectedDate ? `Mostrando citas del ${selectedDate}` : 'Todas las citas'}
              </Text>
            </View>
            <TouchableOpacity
              onPress={() => setCalendarVisible(!calendarVisible)}
              style={styles.calendarButton}
            >
              <FontAwesome name="calendar" size={24} color="#0b4705" />
            </TouchableOpacity>
          </View>
        </View>
      </View>



      {calendarVisible && (
        <View style={styles.calendarContainer}>
          <Calendar
            markedDates={markedDates}
            onDayPress={handleDayPress}
            markingType={'dot'}
            theme={{
              backgroundColor: '#FFFFFF',
              calendarBackground: '#FFFFFF',
              textSectionTitleColor: '#0b4705',
              selectedDayBackgroundColor: '#0b4705',
              selectedDayTextColor: '#FFFFFF',
              todayTextColor: '#FF5722',
              dayTextColor: '#2d4150',
              textDisabledColor: '#d9e1e8',
              arrowColor: '#0b4705',
              monthTextColor: '#0b4705',
              indicatorColor: '#0b4705',
              textDayFontWeight: '500',
              textMonthFontWeight: 'bold',
              textDayHeaderFontWeight: '500',
              textDayFontSize: 14,
              textMonthFontSize: 16,
              textDayHeaderFontSize: 14

            }}
          />
          <TouchableOpacity
            onPress={() => setSelectedDate(null)}
            style={styles.clearDateButton}
          >
            <Text style={styles.clearDateText}>Mostrar todas las citas</Text>
          </TouchableOpacity>
        </View>
      )}

      {loading ? (
        <View style={styles.loadingContainer}>
          <ActivityIndicator size="large" color="#0b4705" />
          <Text style={styles.loadingText}>Cargando citas...</Text>
        </View>
      ) : error ? (
        <View style={styles.errorContainer}>
          <Ionicons name="warning-outline" size={40} color="#e74c3c" />
          <Text style={styles.errorText}>{error}</Text>
          <TouchableOpacity
            onPress={fetchCitas}
            style={styles.retryButton}
          >
            <Text style={styles.retryButtonText}>Reintentar</Text>
          </TouchableOpacity>
        </View>
      ) : filteredCitas.length === 0 ? (
        <View style={styles.emptyContainer}>
          <Ionicons name="calendar-outline" size={60} color="#9E9E9E" />
          <Text style={styles.emptyText}>
            {selectedDate
              ? 'No hay citas para esta fecha'
              : 'No hay citas registradas'}
          </Text>
          <TouchableOpacity
            onPress={() => setCalendarVisible(true)}
            style={styles.checkCalendarButton}
          >
            <Text style={styles.checkCalendarText}>Ver calendario</Text>
          </TouchableOpacity>
        </View>
      ) : (
        <FlatList
          data={filteredCitas}
          renderItem={renderCitaItem}
          keyExtractor={item => item.idcita.toString()}
          contentContainerStyle={styles.listContainer}
          ListHeaderComponent={
            <Text style={styles.resultsCount}>
              {filteredCitas.length} {filteredCitas.length === 1 ? 'cita encontrada' : 'citas encontradas'}
            </Text>
          }
        />
      )}


      <Modal
        animationType="slide"
        transparent={true}
        visible={modalVisible}
        onRequestClose={() => setModalVisible(false)}
      >
        <View style={styles.modalOverlay}>
          <View style={styles.modalContainer}>
            <View style={styles.modalHeader}>
              <Text style={styles.modalTitle}>Responder a la cita</Text>
              <TouchableOpacity
                onPress={() => setModalVisible(false)}
                style={styles.modalCloseButton}
              >
                <Ionicons name="close" size={24} color="#666" />
              </TouchableOpacity>
            </View>

            <View style={styles.modalBody}>
              <View style={styles.modalInfoRow}>
                <Ionicons name="calendar-outline" size={18} color="#666" />
                <Text style={styles.modalInfoText}>
                  {selectedCita?.fechacita} - {selectedCita?.horacita}
                </Text>
              </View>

              <View style={styles.modalInfoRow}>
                <Ionicons name="home-outline" size={18} color="#666" />
                <Text style={styles.modalInfoText}>Apartamento: {selectedCita?.apa}</Text>
              </View>

              <View style={styles.modalInfoRow}>
                <Ionicons name="document-text-outline" size={18} color="#666" />
                <Text style={styles.modalInfoText}>Tipo: {selectedCita?.tipocita}</Text>
              </View>

              <TextInput
                style={styles.respuestaInput}
                multiline
                placeholder="Escribe tu respuesta aquí..."
                placeholderTextColor="#999"
                value={respuesta}
                onChangeText={setRespuesta}
              />
            </View>

            <View style={styles.modalFooter}>
              <TouchableOpacity
                style={[styles.modalButton, styles.cancelButton]}
                onPress={() => {
                  setModalVisible(false);
                  setRespuesta('');
                }}
              >
                <Text style={styles.modalButtonText}>Cancelar</Text>
              </TouchableOpacity>

              <TouchableOpacity
                style={[styles.modalButton, styles.sendButton]}
                onPress={handleResponder}
                disabled={!respuesta.trim()}
              >
                <Text style={styles.modalButtonText}>Enviar Respuesta</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>


      <View style={styles.bottomNav}>
            <TouchableOpacity
                 style={styles.navItem}
                 onPress={() => navigation.navigate('AdminPrincipal')}
               >
                 <FontAwesome name="home" size={24} color="#ecf0f1" />
                 <Text style={styles.navText}>Inicio</Text>
               </TouchableOpacity>
       
               <TouchableOpacity
                 style={styles.navItem}
                 onPress={() => navigation.navigate('PerfilAdmin')}
               >
                 <FontAwesome name="user" size={24} color="#ecf0f1" />
                 <Text style={styles.navText}>Perfil</Text>
               </TouchableOpacity>
       
             
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#F5F5F5',
  },
  header: {
    backgroundColor: '#FFFFFF',
    paddingVertical: 15,
    paddingHorizontal: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#E0E0E0',
    elevation: 2,
  },
  headerContent: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
  },
  headerTitleContainer: {
    flex: 1,
    marginHorizontal: 15,
  },

  userInfo: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  logo: {
    width: 86,
    height: 70,
    borderRadius: 3,
    borderColor: '#d5dbdb',
  },
  welcomeText: {
    fontSize: 22,
    color: '#083004',
    fontWeight: '900',
    fontFamily: 'sans-serif-light',
  },
  userName: {
    fontSize: 27,
    fontWeight: '900',
    color: '#083004',
    fontFamily: 'sans-serif-light',
  },
  notificationIcon: {
    position: 'relative',
    backgroundColor: '#fff',
    padding: 10,
    borderRadius: 20,
    color: '#fff',

  },
  notificationBadge: {
    position: 'absolute',
    top: 7,
    right: 5,
    width: 9,
    height: 9,
    borderRadius: 7,
    backgroundColor: '#e74c3c',
  },
  headerTitle: {
    color: '#0b4705',
    fontSize: 20,
    fontWeight: 'bold',
    textAlign: 'center',
  },
  headerSubtitle: {
    color: '#757575',
    fontSize: 12,
    textAlign: 'center',
    marginTop: 3,
  },
  calendarButton: {
    padding: 5,
  },
  calendarContainer: {
    backgroundColor: '#FFFFFF',
    padding: 10,
    marginBottom: 10,
    elevation: 2,
  },
  clearDateButton: {
    padding: 10,
    alignItems: 'center',
  },
  clearDateText: {
    color: '#0b4705',
    fontWeight: '500',
  },
  listContainer: {
    padding: 10,
    paddingBottom: 70, // Para evitar que el bottom nav tape contenido
  },
  resultsCount: {
    color: '#757575',
    fontSize: 14,
    marginBottom: 10,
    textAlign: 'right',
  },
  citaItem: {
    borderRadius: 8,
    padding: 15,
    marginBottom: 12,
    borderLeftWidth: 8,
    elevation: 1,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.1,
    shadowRadius: 2,
  },
  citaHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 8,
  },
  citaTypeBadge: {
    backgroundColor: '#0b4705',
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 12,
  },
  citaTypeText: {
    color: '#FFFFFF',
    fontSize: 12,
    fontWeight: 'bold',
  },
  citaTime: {
    fontSize: 14,
    color: '#424242',
    fontWeight: '500',
  },
  citaDetails: {
    marginBottom: 10,
  },
  citaApartamento: {
    fontSize: 14,
    color: '#424242',
    marginBottom: 5,
  },
  citaStatusContainer: {
    alignSelf: 'flex-start',
  },
  citaEstado: {
    fontSize: 12,
    fontWeight: 'bold',
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 10,
    overflow: 'hidden',
  },
  estadoPendiente: {
    backgroundColor: '#FFF3E0',
    color: '#FF8F00',
  },
  estadoRespondida: {
    backgroundColor: '#E8F5E9',
    color: '#2E7D32',
  },
  estadoDefault: {
    backgroundColor: '#EEEEEE',
    color: '#616161',
  },
  respuestaContainer: {
    marginTop: 10,
    paddingTop: 10,
    borderTopWidth: 1,
    borderTopColor: '#E0E0E0',
  },
  respuestaLabel: {
    fontSize: 12,
    color: '#757575',
    marginBottom: 5,
    fontWeight: 'bold',
  },
  citaRespuesta: {
    fontSize: 14,
    color: '#424242',
    lineHeight: 20,
  },
  actionsContainer: {
    flexDirection: 'row',
    justifyContent: 'flex-end',
    marginTop: 10,
  },
  actionButton: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 4,
    marginLeft: 8,
  },
  responderButton: {
    backgroundColor: '#0b4705',
  },
  deleteButton: {
    backgroundColor: '#D32F2F',
  },
  buttonText: {
    color: '#FFFFFF',
    fontSize: 14,
    fontWeight: '500',
    marginLeft: 5,
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: 20,
  },
  loadingText: {
    marginTop: 15,
    color: '#0b4705',
    fontSize: 16,
  },
  errorContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: 20,
  },
  errorText: {
    marginTop: 15,
    color: '#e74c3c',
    fontSize: 16,
    textAlign: 'center',
    marginBottom: 20,
  },
  retryButton: {
    backgroundColor: '#0b4705',
    paddingHorizontal: 20,
    paddingVertical: 10,
    borderRadius: 5,
  },
  retryButtonText: {
    color: '#FFFFFF',
    fontWeight: '500',
  },
  emptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: 40,
  },
  emptyText: {
    marginTop: 15,
    color: '#757575',
    fontSize: 16,
    textAlign: 'center',
  },
  checkCalendarButton: {
    marginTop: 20,
    backgroundColor: '#0b4705',
    paddingHorizontal: 20,
    paddingVertical: 10,
    borderRadius: 5,
  },
  checkCalendarText: {
    color: '#FFFFFF',
    fontWeight: '500',
  },
  modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.5)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  modalContainer: {
    backgroundColor: '#FFFFFF',
    borderRadius: 10,
    width: '90%',
    maxHeight: '80%',
  },
  modalHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 15,
    borderBottomWidth: 1,
    borderBottomColor: '#E0E0E0',
  },
  modalTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#0b4705',
    flex: 1,
  },
  modalCloseButton: {
    padding: 5,
  },
  modalBody: {
    padding: 15,
  },
  modalInfoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 10,
  },
  modalInfoText: {
    fontSize: 14,
    color: '#424242',
    marginLeft: 8,
  },
  respuestaInput: {
    borderWidth: 1,
    borderColor: '#BDBDBD',
    borderRadius: 5,
    padding: 12,
    marginTop: 15,
    minHeight: 120,
    textAlignVertical: 'top',
    fontSize: 14,
    color: '#424242',
  },
  modalFooter: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    padding: 15,
    borderTopWidth: 1,
    borderTopColor: '#E0E0E0',
  },
  modalButton: {
    flex: 1,
    padding: 12,
    borderRadius: 5,
    alignItems: 'center',
  },
  cancelButton: {
    backgroundColor: '#ab140c',
    marginRight: 10,
  },
  sendButton: {
    backgroundColor: '#0b4705',
  },
  modalButtonText: {
    color: '#FFFFFF',
    fontWeight: '500',
  },
  bottomNav: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    alignItems: 'center',
    backgroundColor: '#031404',
    paddingVertical: 12,
    position: 'absolute',
    bottom: 0,
    left: 0,
    right: 0,
  },
  navItem: {
    alignItems: 'center',
    paddingHorizontal: 10,
  },
  navText: {
    fontSize: 12,
    color: '#ecf0f1',
    marginTop: 4,
    fontWeight: '500'
  },
  welcomeContainer: {
    marginLeft: 5,
  },
});

export default Citasadmin;