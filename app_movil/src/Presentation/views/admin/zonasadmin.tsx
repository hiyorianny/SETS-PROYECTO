import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, ScrollView, TouchableOpacity, Image, FlatList, SafeAreaView, ActivityIndicator, Alert } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { useNavigation } from '@react-navigation/native';
import CalendarPicker from 'react-native-calendar-picker';
import { Picker } from '@react-native-picker/picker';
import { useAuth } from '../../components/context/AuthContext';
import { Calendar } from 'react-native-calendars';
import { FontAwesome } from '@expo/vector-icons';


type SolicitudZona = {
  ID_Apartamentooss: string;
  ID_zonaComun: number;
  fechainicio: string;
  fechafinal: string;
  Hora_inicio: string;
  Hora_final: string;
  estado: 'ACEPTADA' | 'PENDIENTE' | 'RECHAZADA';
  nombreZona?: string;
};

type ZonaComun = {
  idZona: number;
  descripcion: string;
  costo_alquiler: string;
};

const ZonasComunesadmin = () => {
  const { user } = useAuth();
  const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
  const [solicitudes, setSolicitudes] = useState<SolicitudZona[]>([]);
  const [zonasComunes, setZonasComunes] = useState<ZonaComun[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [selectedDate, setSelectedDate] = useState<Date | null>(null);
  const [filteredSolicitudes, setFilteredSolicitudes] = useState<SolicitudZona[]>([]);
  const [selectedZona, setSelectedZona] = useState<number | 'all'>('all');
  const [markedDates, setMarkedDates] = useState<any>({});

  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);

        const zonasResponse = await fetch('http://192.168.1.105:3001/api/zonas-comunes');
        if (!zonasResponse.ok) throw new Error('Error al obtener zonas comunes');
        const zonasData = await zonasResponse.json();
        setZonasComunes(zonasData);

        const solicitudesResponse = await fetch('http://192.168.1.105:3001/api/solicitudes-zonas');
        if (!solicitudesResponse.ok) throw new Error('Error al obtener solicitudes');
        let solicitudesData = await solicitudesResponse.json();

        solicitudesData = solicitudesData.map((solicitud: SolicitudZona) => ({
          ...solicitud,
          nombreZona: zonasData.find((zona: ZonaComun) => zona.idZona === solicitud.ID_zonaComun)?.descripcion || 'Desconocido'
        }));

        setSolicitudes(solicitudesData);
        prepareMarkedDates(solicitudesData);
      } catch (err) {
        setError(err instanceof Error ? err.message : 'Error desconocido');
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  useEffect(() => {
    filterSolicitudes();
  }, [selectedDate, selectedZona, solicitudes]);

  const prepareMarkedDates = (solicitudesData: SolicitudZona[]) => {
    const dates: any = {};


    const solicitudesPorFecha: { [key: string]: SolicitudZona[] } = {};

    solicitudesData.forEach(solicitud => {
      const dateStr = solicitud.fechainicio.split('T')[0];

      if (!solicitudesPorFecha[dateStr]) {
        solicitudesPorFecha[dateStr] = [];
      }
      solicitudesPorFecha[dateStr].push(solicitud);
    });


    Object.keys(solicitudesPorFecha).forEach(dateStr => {
      const solicitudes = solicitudesPorFecha[dateStr];

      const counts = {
        ACEPTADA: 1,
        PENDIENTE: 2,
        RECHAZADA: 0
      };

      solicitudes.forEach(solicitud => {
        counts[solicitud.estado]++;
      });


      const dots = [];
      if (counts.ACEPTADA > 1) dots.push({ key: 'ACEPTADA', color: '#072104' });
      if (counts.PENDIENTE > 2) dots.push({ key: 'PENDIENTE', color: '#decb00' });
      if (counts.RECHAZADA > 0) dots.push({ key: 'RECHAZADA', color: '#F44336' });

      dates[dateStr] = {
        selected: selectedDate ? dateStr === selectedDate.toISOString().split('T')[0] : false,
        selectedColor: '#083004',
        marked: true,
        dots: dots
      };
    });

    setMarkedDates(dates);
  };
  const filterSolicitudes = () => {
    let filtered = [...solicitudes];

    if (selectedDate) {
      const selectedDateStr = selectedDate.toISOString().split('T')[0];
      filtered = filtered.filter(solicitud =>
        solicitud.fechainicio.split('T')[0] === selectedDateStr
      );
    }

    if (selectedZona !== 'all') {
      filtered = filtered.filter(solicitud =>
        solicitud.ID_zonaComun === selectedZona
      );
    }

    setFilteredSolicitudes(filtered);
  };

  const handleDateChange = (date: Date) => {
    setSelectedDate(date);
    prepareMarkedDates(solicitudes);
  };
  

  const handleChangeStatus = async (idApartamento: string, idZona: number, fechaInicio: string, nuevoEstado: 'ACEPTADA' | 'PENDIENTE' | 'RECHAZADA') => {
    try {

      const fechaFormateada = fechaInicio.split('T')[0];

      console.log('Intentando actualizar estado:', {
        idApartamento,
        idZona,
        fechaFormateada,
        nuevoEstado
      });

      const response = await fetch('http://192.168.1.105:3001/api/actualizar-estado-solicitud', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          ID_Apartamentooss: idApartamento,
          ID_zonaComun: idZona,
          fechainicio: fechaFormateada,
          estado: nuevoEstado
        }),
      });

      const responseData = await response.json();

      if (!response.ok) {
        console.error('Error en la respuesta del servidor:', responseData);
        throw new Error(responseData.error || 'Error al actualizar estado');
      }

      console.log('Respuesta del servidor:', responseData);


      const updatedSolicitudes = solicitudes.map(solicitud => {
        if (solicitud.ID_Apartamentooss === idApartamento &&
          solicitud.ID_zonaComun === idZona &&
          solicitud.fechainicio.split('T')[0] === fechaFormateada) {
          return { ...solicitud, estado: nuevoEstado };
        }
        return solicitud;
      });

      setSolicitudes(updatedSolicitudes);
      prepareMarkedDates(updatedSolicitudes);

      Alert.alert('Éxito', 'Estado actualizado correctamente');
    } catch (err) {
      console.error('Error completo al cambiar estado:', err);
      Alert.alert('Error', err instanceof Error ? err.message : 'No se pudo actualizar el estado');
    }
  };

  const renderSolicitudItem = ({ item }: { item: SolicitudZona }) => (
    <View style={[styles.solicitudItem, styles[`estado${item.estado}`]]}>
      <View style={styles.solicitudHeader}>
        <Text style={styles.solicitudTitle}>Apartamento: {item.ID_Apartamentooss}</Text>
        <Text style={styles.solicitudText}>{item.estado}</Text>
      </View>
      <Text style={styles.solicitudText}>Zona: {item.nombreZona}</Text>
      <Text style={styles.solicitudText}>Fecha: {item.fechainicio} - {item.fechafinal}</Text>
      <Text style={styles.solicitudText}>Hora: {item.Hora_inicio} - {item.Hora_final}</Text>

      <View style={styles.actionsContainer}>
        <TouchableOpacity
          style={[styles.actionButton, styles.acceptButton]}
          onPress={() => handleChangeStatus(item.ID_Apartamentooss, item.ID_zonaComun, item.fechainicio, 'ACEPTADA')}
        >
          <Text style={styles.actionText}>Aceptar</Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={[styles.actionButton, styles.rejectButton]}
          onPress={() => handleChangeStatus(item.ID_Apartamentooss, item.ID_zonaComun, item.fechainicio, 'RECHAZADA')}
        >
          <Text style={styles.actionText}>Rechazar</Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={[styles.actionButton, styles.pendingButton]}
          onPress={() => handleChangeStatus(item.ID_Apartamentooss, item.ID_zonaComun, item.fechainicio, 'PENDIENTE')}
        >
          <Text style={styles.actionText}>Pendiente</Text>
        </TouchableOpacity>
      </View>
    </View>
  );

  return (
    <SafeAreaView style={styles.safeArea}>
      <ScrollView style={styles.container} contentContainerStyle={styles.scrollContent}>
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
          <TouchableOpacity
            style={styles.notificationIcon}
            onPress={() => navigation.navigate('Notificacionesadmin')}
          >
            <FontAwesome name="bell" size={28} color="#19800f" />
            <View style={styles.notificationBadge} />
          </TouchableOpacity>
        </View>

        <View style={styles.header}>
          <TouchableOpacity onPress={() => navigation.goBack()}>
            <FontAwesome name="arrow-left" size={24} color="#0b4705" />
          </TouchableOpacity>
          <Text style={styles.title}>Administrar Zonas Comunes</Text>
        </View>

        <View style={styles.filterContainer}>
          <Text style={styles.filterTitle}>Filtrar por fecha:</Text>
          <Calendar
            onDayPress={(day: { dateString: string | number | Date; }) => handleDateChange(new Date(day.dateString))}
            markedDates={markedDates}
            theme={{
              selectedDayBackgroundColor: '#083004',
              selectedDayTextColor: '#FFFFFF',
              todayTextColor: '#083004',
              arrowColor: '#083004',
              monthTextColor: '#083004',
              textDayFontWeight: '500',
              textMonthFontWeight: 'bold',
              textDayHeaderFontWeight: '500'
            }}
          />
          <TouchableOpacity
            style={styles.clearDateButton}
            onPress={() => setSelectedDate(null)}
          >
            <Text style={styles.clearDateText}>Limpiar filtro de fecha</Text>
          </TouchableOpacity>

          <Text style={styles.filterTitle}>Filtrar por zona:</Text>
          <Picker
            selectedValue={selectedZona}
            onValueChange={(itemValue) => setSelectedZona(itemValue)}
            style={styles.picker}
          >
            <Picker.Item label="Todas las zonas" value="all" />
            {zonasComunes.map(zona => (
              <Picker.Item key={zona.idZona} label={zona.descripcion} value={zona.idZona} />
            ))}
          </Picker>
        </View>

        {loading ? (
          <ActivityIndicator size="large" color="#083004" style={styles.loading} />
        ) : error ? (
          <Text style={styles.errorText}>{error}</Text>
        ) : (
          <>
            <Text style={styles.sectionTitle}>
              {selectedDate
                ? `Solicitudes para el ${selectedDate.toLocaleDateString()}`
                : 'Todas las solicitudes'}
              {selectedZona !== 'all'
                ? ` - ${zonasComunes.find(z => z.idZona === selectedZona)?.descripcion}`
                : ''}
            </Text>

            {filteredSolicitudes.length === 0 ? (
              <Text style={styles.noResults}>No hay solicitudes para los filtros seleccionados</Text>
            ) : (
              <FlatList
                data={filteredSolicitudes}
                renderItem={renderSolicitudItem}
                keyExtractor={(item, index) => `${item.ID_Apartamentooss}-${item.ID_zonaComun}-${item.fechainicio}-${index}`}
                scrollEnabled={false}
              />
            )}
          </>
        )}
      </ScrollView>

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
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  safeArea: {
    flex: 1,
    backgroundColor: '#fff',
  },
  container: {
    flex: 1,
  },
  scrollContent: {
    padding: 15,
    paddingBottom: 80,
  },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    marginBottom: 20,
  },
  userInfo: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  logo: {
    width: 86,
    height: 86,
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
  },
  notificationBadge: {
    position: 'absolute',
    top: 7,
    right: 5,
    width: 9,
    height: 9,
    borderRadius: 7,
    backgroundColor: '#041a05',
  },
  title: {
    fontSize: 22,
    fontWeight: 'bold',
    color: '#041a05',
    marginLeft: 10,
    flex: 1,
    textAlign: 'center',
  },
  filterContainer: {
    marginBottom: 20,
    backgroundColor: '#f5f5f5',
    padding: 15,
    borderRadius: 10,
  },
  filterTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#041a05',
    marginBottom: 10,
  },
  picker: {
    width: '100%',
    backgroundColor: '#FFF',
    borderRadius: 5,
    marginBottom: 10,
  },
  loading: {
    marginVertical: 20,
  },
  errorText: {
    color: 'red',
    textAlign: 'center',
    marginVertical: 20,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#041a05',
    marginBottom: 15,
    textAlign: 'center',
  },
  noResults: {
    textAlign: 'center',
    color: '#666',
    marginVertical: 20,
    fontSize: 16,
  },
  solicitudItem: {
    backgroundColor: '#0d4706',
    borderRadius: 8,
    padding: 15,
    marginBottom: 12,
    borderWidth: 1,
    borderColor: '#ddd',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 2,
  },
  estadoACEPTADA: {
    borderLeftWidth: 5,
    borderLeftColor: '#072104',
  },
  estadoPENDIENTE: {
    borderLeftWidth: 5,
    borderLeftColor: '#FFC107',
  },
  estadoRECHAZADA: {
    borderLeftWidth: 5,
    borderLeftColor: '#F44336',
  },
  solicitudHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 8,
  },
  solicitudTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#FFF',
  },
  solicitudStatus: {
    fontSize: 14,
    fontWeight: 'bold',
    textTransform: 'uppercase',
  },
  solicitudText: {
    fontSize: 14,
    color: '#FFF',
    marginBottom: 5,
  },
  actionsContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginTop: 10,
  },
  actionButton: {
    paddingVertical: 8,
    paddingHorizontal: 12,
    borderRadius: 5,
    alignItems: 'center',
    justifyContent: 'center',
    flex: 1,
    marginHorizontal: 3,
  },
  acceptButton: {
    backgroundColor: '#4CAF50',
  },
  rejectButton: {
    backgroundColor: '#F44336',
  },
  pendingButton: {
    backgroundColor: '#FFC107',
  },
  actionText: {
    color: '#ffff',
    fontWeight: 'bold',
    fontSize: 12,
  },
  bottomNav: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    alignItems: 'center',
    backgroundColor: '#0a120a',
    paddingVertical: 12,
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
    fontSize: 14,
    color: '#ecf0f1',
    marginTop: 4,
    fontWeight: '900'
  },
  welcomeContainer: {
    marginLeft: 5,
  },
  clearDateButton: {
    backgroundColor: '#E0E0E0',
    padding: 10,
    borderRadius: 5,
    alignItems: 'center',
    marginBottom: 15,
  },
  clearDateText: {
    color: '#083004',
    fontWeight: 'bold',
  },
});

export default ZonasComunesadmin;