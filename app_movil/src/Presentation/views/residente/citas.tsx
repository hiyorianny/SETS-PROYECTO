import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, ScrollView, Image, TouchableOpacity, FlatList, SafeAreaView, TextInput, Alert, Modal, TouchableWithoutFeedback, KeyboardAvoidingView, Platform } from 'react-native';
import { Calendar } from 'react-native-calendars';
import { MaterialIcons } from '@expo/vector-icons';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { useNavigation } from '@react-navigation/native';
import DateTimePicker from '@react-native-community/datetimepicker';
import { useAuth } from '../../components/context/AuthContext';
import { FontAwesome } from '@expo/vector-icons';

type Cita = {
  idcita: number;
  fechacita: string;
  horacita: string;
  tipocita: string;
  apa: string;
  respuesta: string;
  estado: 'pendiente' | 'respondida' | '';
};

type MarkedDates = {
  [date: string]: {
    marked?: boolean;
    dotColor?: string;
    selected?: boolean;
    selectedColor?: string;
    selectedTextColor?: string;
  };
};

const CitasScreen = () => {
  const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
  const { user } = useAuth();
  const [citas, setCitas] = useState<Cita[]>([]);
  const [loading, setLoading] = useState(true);
  const [selectedDate, setSelectedDate] = useState<string>(new Date().toISOString().split('T')[0]);
  const [markedDates, setMarkedDates] = useState<MarkedDates>({});
  const [formData, setFormData] = useState({
    tipoCita: 'Administrativo',
    fecha: '',
    hora: '',
    apartamento: ''
  });
  const [showTimePicker, setShowTimePicker] = useState(false);
  const [selectedTime, setSelectedTime] = useState(new Date());

  const tiposCita = [
    { label: 'Administrativo (1h)', value: 'Administrativo' },
    { label: 'Reclamo (30min)', value: 'Reclamo' },
    { label: 'Duda (15min)', value: 'Duda' },
    { label: 'Otro (30min)', value: 'Otro' }
  ];

  useEffect(() => {
    fetchCitas();
  }, []);

  const fetchCitas = async () => {
    try {
      const response = await fetch('http://192.168.1.105:3001/api/citas');
      if (!response.ok) {
        throw new Error('Error al obtener citas');
      }
      const data = await response.json();
      setCitas(data);

      const dates: MarkedDates = {};
      data.forEach((cita: Cita) => {
        const fecha = cita.fechacita.split('T')[0];
        dates[fecha] = { 
          marked: true, 
          dotColor: '#FF5252',
          selected: fecha === selectedDate,
          selectedColor: fecha === selectedDate ? '#1e871e' : undefined,
          selectedTextColor: fecha === selectedDate ? 'white' : undefined
        };
      });
      
      if (selectedDate && !dates[selectedDate]) {
        dates[selectedDate] = {
          selected: true,
          selectedColor: '#1e871e',
          selectedTextColor: 'white'
        };
      }
      
      setMarkedDates(dates);
    } catch (err) {
      console.error('Error fetching citas:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleDateSelect = (date: string) => {
    const updatedDates = {...markedDates};
    
    Object.keys(updatedDates).forEach(key => {
      if (updatedDates[key].selected) {
        updatedDates[key] = {
          ...updatedDates[key],
          selected: false
        };
      }
    });
    
    updatedDates[date] = {
      ...updatedDates[date],
      marked: updatedDates[date]?.marked || false,
      dotColor: '#FF5252',
      selected: true,
      selectedColor: '#1e871e',
      selectedTextColor: 'white'
    };
    
    setMarkedDates(updatedDates);
    setSelectedDate(date);
    setFormData({ ...formData, fecha: date });
  };

  const handleTimePress = () => {
    setShowTimePicker(true);
  };

  const handleTimeChange = (event: any, selectedDate?: Date) => {
    setShowTimePicker(false);
    if (selectedDate) {
      setSelectedTime(selectedDate);
      const hours = selectedDate.getHours().toString().padStart(2, '0');
      const minutes = selectedDate.getMinutes().toString().padStart(2, '0');
      setFormData({ ...formData, hora: `${hours}:${minutes}` });
    }
  };

  const handleSubmit = async () => {
    if (!formData.tipoCita || !formData.fecha || !formData.hora || !formData.apartamento) {
      Alert.alert('Error', 'Por favor complete todos los campos');
      return;
    }

    try {
      const response = await fetch('http://192.168.1.105:3001/api/citassolicitud', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          fechacita: formData.fecha,
          horacita: formData.hora + ':00',
          tipocita: formData.tipoCita,
          apa: formData.apartamento,
          estado: 'pendiente'
        })
      });

      const contentType = response.headers.get('content-type');
      if (!contentType || !contentType.includes('application/json')) {
        const text = await response.text();
        throw new Error(`Respuesta inesperada: ${text}`);
      }

      const responseData = await response.json();

      if (!response.ok) {
        throw new Error(responseData.error || 'Error al crear cita');
      }

      Alert.alert('Éxito', 'Cita agendada correctamente');
      fetchCitas();
      setFormData({
        tipoCita: 'Administrativo',
        fecha: '',
        hora: '',
        apartamento: ''
      });
    } catch (err) {
      console.error('Error al crear cita:', err);
      Alert.alert('Error', 'No se pudo agendar la cita. Por favor intente nuevamente.');
    }
  };

  const handleDelete = async (id: number) => {
    Alert.alert(
      'Eliminar cita',
      '¿Estás seguro de que deseas eliminar esta cita?',
      [
        { text: 'Cancelar', style: 'cancel' },
        {
          text: 'Eliminar',
          onPress: async () => {
            try {
              const response = await fetch(`http://192.168.1.105:3001/api/citas/${id}`, {
                method: 'DELETE'
              });

              if (!response.ok) {
                throw new Error('Error al eliminar cita');
              }

              Alert.alert('Éxito', 'Cita eliminada correctamente');
              fetchCitas();
            } catch (err) {
              console.error('Error al eliminar cita:', err);
              Alert.alert('Error', 'No se pudo eliminar la cita');
            }
          }
        }
      ]
    );
  };

  const renderCitaItem = ({ item }: { item: Cita }) => (
    <View style={styles.citaItem}>
      <View style={styles.citaInfo}>
        <Text style={styles.citaTipo}>{item.tipocita}</Text>
        <Text style={styles.citaFecha}>{item.fechacita} - {item.horacita.substring(0, 5)}</Text>
        <Text style={styles.citaEstado}>Estado: {item.estado === 'respondida' ? 'Respondida' : 'Pendiente'}</Text>
        {item.respuesta && <Text style={styles.citaRespuesta}>Respuesta: {item.respuesta}</Text>}
      </View>
      <TouchableOpacity
        style={styles.deleteButton}
        onPress={() => handleDelete(item.idcita)}
      >
        <MaterialIcons name="delete" size={24} color="#e74c3c" />
      </TouchableOpacity>
    </View>
  );

  return (
    <KeyboardAvoidingView
      behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
      style={styles.flexContainer}
    >
      <SafeAreaView style={styles.safeArea}>
        <ScrollView 
          style={styles.container}
          contentContainerStyle={styles.scrollContent}
          keyboardShouldPersistTaps="handled"
        >
          <View style={styles.header}>
            <TouchableOpacity onPress={() => navigation.goBack()}>
              <MaterialIcons name="arrow-back" size={24} color="#1e871e" />
            </TouchableOpacity>
            <View style={styles.headerContent}>
              <View style={styles.userInfo}>
                <Image
                  source={require('./img/resi.png')}
                  style={styles.logo}
                />
                <View style={styles.welcomeContainer}>
                  <Text style={styles.userName}>Residente</Text>
                  <Text style={styles.welcomeText}>
                    {user ? `${user.Usuario} ` : 'Usuario'}
                  </Text>
                  <Text style={styles.userName}>Gestión de Citas</Text>
                </View>
              </View>
              <TouchableOpacity
                style={styles.notificationIcon}
                onPress={() => navigation.navigate('Notiresidente')}
              >
                <FontAwesome name="bell" size={24} color="#1d4a1d" />
                <View style={styles.notificationBadge} />
              </TouchableOpacity>
            </View>
            <View style={{ width: 24 }} />
          </View>

          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Calendario de Citas</Text>
            <Calendar
              current={selectedDate}
              onDayPress={(day: { dateString: string; }) => handleDateSelect(day.dateString)}
              markedDates={markedDates}
              theme={{
                calendarBackground: '#fff',
                selectedDayBackgroundColor: '#1e871e',
                selectedDayTextColor: '#fff',
                todayTextColor: '#1e871e',
                dayTextColor: '#2d4150',
                textDisabledColor: '#d9e1e8',
                dotColor: '#FF5252',
                selectedDotColor: '#fff',
                arrowColor: '#1e871e',
                monthTextColor: '#1e871e',
                textDayFontWeight: '300',
                textMonthFontWeight: 'bold',
                textDayHeaderFontWeight: '300',
                textDayFontSize: 16,
                textMonthFontSize: 16,
                textDayHeaderFontSize: 16
              }}
            />
            <View style={styles.legendContainer}>
              <View style={styles.legendItem}>
                <View style={[styles.legendDot, {backgroundColor: '#1e871e'}]} />
                <Text style={styles.legendText}>Día seleccionado</Text>
              </View>
              <View style={styles.legendItem}>
                <View style={[styles.legendDot, {backgroundColor: '#FF5252'}]} />
                <Text style={styles.legendText}>Fecha con cita</Text>
              </View>
            </View>
          </View>

          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Agendar Nueva Cita</Text>
            <View style={styles.formGroup}>
              <Text style={styles.label}>Tipo de cita:</Text>
              <View style={styles.radioGroup}>
                {tiposCita.map((tipo) => (
                  <TouchableOpacity
                    key={tipo.value}
                    style={[
                      styles.radioButton,
                      formData.tipoCita === tipo.value && styles.radioButtonSelected
                    ]}
                    onPress={() => setFormData({ ...formData, tipoCita: tipo.value })}
                  >
                    <Text style={[
                      styles.radioText,
                      formData.tipoCita === tipo.value && styles.radioTextSelected
                    ]}>
                      {tipo.label}
                    </Text>
                  </TouchableOpacity>
                ))}
              </View>
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>Fecha:</Text>
              <TextInput
                style={styles.input}
                value={formData.fecha}
                placeholder="Seleccione una fecha en el calendario"
                editable={false}
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>Hora:</Text>
              <TouchableOpacity onPress={handleTimePress}>
                <TextInput
                  style={styles.input}
                  value={formData.hora}
                  placeholder="Seleccione una hora"
                  editable={false}
                  pointerEvents="none"
                />
              </TouchableOpacity>
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>Apartamento:</Text>
              <TextInput
                style={styles.input}
                value={formData.apartamento}
                onChangeText={(text) => setFormData({ ...formData, apartamento: text })}
                placeholder="Ej: 101A, 40AA, etc."
                editable={true}
              />
            </View>

            <TouchableOpacity
              style={styles.submitButton}
              onPress={handleSubmit}
            >
              <Text style={styles.submitButtonText}>Agendar Cita</Text>
            </TouchableOpacity>
          </View>

          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Mis Citas</Text>
            {loading ? (
              <Text style={styles.loadingText}>Cargando citas...</Text>
            ) : citas.length === 0 ? (
              <Text style={styles.noCitasText}>No tienes citas agendadas</Text>
            ) : (
              <FlatList
                data={citas}
                renderItem={renderCitaItem}
                keyExtractor={(item) => item.idcita.toString()}
                scrollEnabled={false}
                contentContainerStyle={styles.citasList}
              />
            )}
          </View>
        </ScrollView>

        {showTimePicker && (
          <Modal
            transparent={true}
            animationType="slide"
            visible={showTimePicker}
            onRequestClose={() => setShowTimePicker(false)}
          >
            <TouchableWithoutFeedback onPress={() => setShowTimePicker(false)}>
              <View style={styles.modalOverlay} />
            </TouchableWithoutFeedback>
            <View style={styles.timePickerContainer}>
              <DateTimePicker
                value={selectedTime}
                mode="time"
                display="spinner"
                onChange={handleTimeChange}
                minuteInterval={15}
                locale="es_ES"
              />
              <TouchableOpacity
                style={styles.timePickerButton}
                onPress={() => setShowTimePicker(false)}
              >
                <Text style={styles.timePickerButtonText}>Aceptar</Text>
              </TouchableOpacity>
            </View>
          </Modal>
        )}

        <View style={styles.bottomNav}>
          <TouchableOpacity
            style={styles.navItem}
            onPress={() => navigation.navigate('ResidentePrincipal')}
          >
            <FontAwesome name="home" size={24} color="#fff" />
            <Text style={styles.navText}>Inicio</Text>
          </TouchableOpacity>
          <TouchableOpacity
            style={styles.navItem}
            onPress={() => navigation.navigate('Pagos')}
          >
            <FontAwesome name="money" size={24} color="#fff" />
            <Text style={styles.navText}>Pagos</Text>
          </TouchableOpacity>
          <TouchableOpacity
            style={styles.navItem}
            onPress={() => navigation.navigate('Perfil')}
          >
            <FontAwesome name="user" size={24} color="#fff" />
            <Text style={styles.navText}>Perfil</Text>
          </TouchableOpacity>
        </View>
      </SafeAreaView>
    </KeyboardAvoidingView>
  );
};

const styles = StyleSheet.create({
  flexContainer: {
    flex: 1,
  },
  safeArea: {
    flex: 1,
    backgroundColor: '#fff',
  },
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  scrollContent: {
    padding: 16,
    paddingBottom: 80,
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 20,
  },
  headerContent: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 1,
    justifyContent: 'space-between',
  },
  userInfo: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  logo: {
    width: 50,
    height: 60,
    borderRadius: 50,
    marginRight: 16,
  },
  welcomeText: {
    fontSize: 19,
    color: '#0d330d',
    fontWeight: '900',
  },
  userName: {
    fontSize: 20,
    fontWeight: '900',
    color: '#0d330d',
  },
  notificationIcon: {
    position: 'relative',
  },
  notificationBadge: {
    position: 'absolute',
    top: 0,
    right: 0,
    width: 8,
    height: 8,
    borderRadius: 4,
    backgroundColor: '#FF5252',
  },
  welcomeContainer: {
    marginTop: 10,
  },
  section: {
    backgroundColor: '#fff',
    borderRadius: 10,
    padding: 16,
    marginBottom: 20,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#1e871e',
    marginBottom: 15,
  },
  legendContainer: {
    flexDirection: 'row',
    justifyContent: 'center',
    marginTop: 10,
    gap: 20,
  },
  legendItem: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  legendDot: {
    width: 12,
    height: 12,
    borderRadius: 6,
    marginRight: 5,
  },
  legendText: {
    fontSize: 12,
    color: '#555',
  },
  formGroup: {
    marginBottom: 15,
  },
  label: {
    fontSize: 16,
    color: '#333',
    marginBottom: 8,
    fontWeight: '500',
  },
  input: {
    borderWidth: 1,
    borderColor: '#ddd',
    borderRadius: 8,
    padding: 12,
    fontSize: 16,
    backgroundColor: '#f9f9f9',
  },
  radioGroup: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    justifyContent: 'space-between',
  },
  radioButton: {
    borderWidth: 1,
    borderColor: '#ddd',
    borderRadius: 8,
    padding: 12,
    marginBottom: 10,
    width: '48%',
    alignItems: 'center',
    backgroundColor: '#f9f9f9',
  },
  radioButtonSelected: {
    borderColor: '#1e871e',
    backgroundColor: '#e8f5e9',
  },
  radioText: {
    fontSize: 14,
    color: '#555',
  },
  radioTextSelected: {
    color: '#1e871e',
    fontWeight: 'bold',
  },
  submitButton: {
    backgroundColor: '#1e871e',
    borderRadius: 8,
    padding: 15,
    alignItems: 'center',
    marginTop: 10,
  },
  submitButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  citasList: {
    paddingBottom: 10,
  },
  citaItem: {
    backgroundColor: '#f9f9f9',
    borderRadius: 8,
    padding: 15,
    marginBottom: 10,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    borderLeftWidth: 4,
    borderLeftColor: '#1e871e',
  },
  citaInfo: {
    flex: 1,
  },
  citaTipo: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#1e871e',
    marginBottom: 5,
  },
  citaFecha: {
    fontSize: 14,
    color: '#555',
    marginBottom: 5,
  },
  citaEstado: {
    fontSize: 14,
    color: '#555',
    fontStyle: 'italic',
  },
  citaRespuesta: {
    fontSize: 14,
    color: '#333',
    marginTop: 5,
    fontWeight: '500',
  },
  deleteButton: {
    padding: 8,
    marginLeft: 10,
  },
  loadingText: {
    textAlign: 'center',
    color: '#555',
    marginVertical: 20,
  },
  noCitasText: {
    textAlign: 'center',
    color: '#777',
    marginVertical: 20,
    fontStyle: 'italic',
  },
  modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.5)',
  },
  timePickerContainer: {
    backgroundColor: '#fff',
    padding: 20,
    borderTopLeftRadius: 10,
    borderTopRightRadius: 10,
  },
  timePickerButton: {
    backgroundColor: '#1e871e',
    borderRadius: 8,
    padding: 15,
    alignItems: 'center',
    marginTop: 10,
  },
  timePickerButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  bottomNav: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    alignItems: 'center',
    backgroundColor: '#091f09',
    paddingVertical: 10,
    position: 'absolute',
    bottom: 0,
    left: 0,
    right: 0,
    height: 60,
  },
  navItem: {
    alignItems: 'center',
    paddingHorizontal: 10,
  },
  navText: {
    fontSize: 12,
    color: '#fff',
    marginTop: 4,
    fontWeight: '900',
  },
});

export default CitasScreen;