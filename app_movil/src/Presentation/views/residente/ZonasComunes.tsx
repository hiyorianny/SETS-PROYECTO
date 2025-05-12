import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, ScrollView, TouchableOpacity, Image, FlatList, SafeAreaView, ActivityIndicator, Alert, Modal, TextInput } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { useNavigation } from '@react-navigation/native';
import { Picker } from '@react-native-picker/picker';
import { useAuth } from '../../components/context/AuthContext';
import { Calendar } from 'react-native-calendars';
import { FontAwesome } from '@expo/vector-icons';
import DateTimePicker from '@react-native-community/datetimepicker';

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

const ZonasComunes = () => {
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
  
  const [modalVisible, setModalVisible] = useState(false);
  const [editModalVisible, setEditModalVisible] = useState(false);
  const [currentSolicitud, setCurrentSolicitud] = useState<SolicitudZona | null>(null);
  const [formData, setFormData] = useState({
    ID_Apartamentooss: '', 
    ID_zonaComun: '',
    fechainicio: '',
    fechafinal: '',
    Hora_inicio: '',
    Hora_final: ''
  });
  const [showDatePicker, setShowDatePicker] = useState(false);
  const [showTimePicker, setShowTimePicker] = useState(false);
  const [pickerMode, setPickerMode] = useState<'date' | 'time'>('date');
  const [pickerField, setPickerField] = useState<'fechainicio' | 'fechafinal' | 'Hora_inicio' | 'Hora_final'>('fechainicio');
  const [errors, setErrors] = useState({
    ID_Apartamentooss: '',
    ID_zonaComun: '',
    fechainicio: '',
    fechafinal: '',
    Hora_inicio: '',
    Hora_final: ''
  });

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
      if (counts.ACEPTADA > 1) dots.push({ key: 'ACEPTADA', color: '#031404' });
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

  const handleDateChange = (day: any) => {
    setSelectedDate(new Date(day.dateString));
    prepareMarkedDates(solicitudes);
  };

  const openNewForm = () => {
    setFormData({
      ID_Apartamentooss:  '',
      ID_zonaComun: '',
      fechainicio: '',
      fechafinal: '',
      Hora_inicio: '',
      Hora_final: ''
    });
    setErrors({
      ID_Apartamentooss: '',
      ID_zonaComun: '',
      fechainicio: '',
      fechafinal: '',
      Hora_inicio: '',
      Hora_final: ''
    });
    setModalVisible(true);
  };

  const openEditForm = (solicitud: SolicitudZona) => {
    setCurrentSolicitud(solicitud);
    setFormData({
      ID_Apartamentooss: solicitud.ID_Apartamentooss,
      ID_zonaComun: solicitud.ID_zonaComun.toString(),
      fechainicio: solicitud.fechainicio.split('T')[0],
      fechafinal: solicitud.fechafinal.split('T')[0],
      Hora_inicio: solicitud.Hora_inicio,
      Hora_final: solicitud.Hora_final
    });
    setEditModalVisible(true);
  };

  const handleInputChange = (name: keyof typeof formData, value: string) => {
    setFormData({
      ...formData,
      [name]: value
    });
 
    setErrors({
      ...errors,
      [name]: ''
    });
  };

  const showPicker = (mode: 'date' | 'time', field: typeof pickerField) => {
    setPickerMode(mode);
    setPickerField(field);
    if (mode === 'date') {
      setShowDatePicker(true);
    } else {
      setShowTimePicker(true);
    }
  };

  const handlePickerChange = (event: any, selectedDate?: Date) => {
    if (selectedDate) {
      let value = '';
      if (pickerMode === 'date') {
        value = selectedDate.toISOString().split('T')[0];
      } else {
        const hours = selectedDate.getHours().toString().padStart(2, '0');
        const minutes = selectedDate.getMinutes().toString().padStart(2, '0');
        value = `${hours}:${minutes}`;
      }
      
      handleInputChange(pickerField, value);
    }
    
    setShowDatePicker(false);
    setShowTimePicker(false);
  };

  const validateForm = () => {
    let valid = true;
    const newErrors = {
      ID_Apartamentooss: '',
      ID_zonaComun: '',
      fechainicio: '',
      fechafinal: '',
      Hora_inicio: '',
      Hora_final: ''
    };

    if (!formData.ID_Apartamentooss.trim()) {
      newErrors.ID_Apartamentooss = 'Debe ingresar el número de apartamento';
      valid = false;
    }

    if (!formData.ID_zonaComun) {
      newErrors.ID_zonaComun = 'Debe seleccionar una zona común';
      valid = false;
    }

    if (!formData.fechainicio) {
      newErrors.fechainicio = 'Debe seleccionar una fecha de inicio';
      valid = false;
    }

    if (!formData.fechafinal) {
      newErrors.fechafinal = 'Debe seleccionar una fecha de finalización';
      valid = false;
    } else if (formData.fechainicio && new Date(formData.fechafinal) < new Date(formData.fechainicio)) {
      newErrors.fechafinal = 'La fecha final debe ser posterior a la fecha inicial';
      valid = false;
    }

    if (!formData.Hora_inicio) {
      newErrors.Hora_inicio = 'Debe seleccionar una hora de inicio';
      valid = false;
    }

    if (!formData.Hora_final) {
      newErrors.Hora_final = 'Debe seleccionar una hora de finalización';
      valid = false;
    } else if (formData.Hora_inicio && formData.Hora_final <= formData.Hora_inicio && formData.fechainicio === formData.fechafinal) {
      newErrors.Hora_final = 'La hora final debe ser posterior a la hora inicial';
      valid = false;
    }

    setErrors(newErrors);
    return valid;
  };

  const submitSolicitud = async () => {
    if (!validateForm()) {
      Alert.alert('Error', 'Por favor complete todos los campos correctamente');
      return;
    }

    try {
      const response = await fetch('http://192.168.1.105:3001/api/reservar-zona', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          ID_Apartamentooss: formData.ID_Apartamentooss, 
          ID_zonaComun: parseInt(formData.ID_zonaComun),
          fechainicio: formData.fechainicio,
          fechafinal: formData.fechafinal,
          Hora_inicio: formData.Hora_inicio,
          Hora_final: formData.Hora_final
        }),
      });

      const data = await response.json();

      if (!response.ok) {
        if (data.error.includes('ya está reservada')) {
          Alert.alert('Error', 'La zona ya está reservada en ese horario');
        } else {
          throw new Error(data.error || 'Error al crear la solicitud');
        }
        return;
      }


      const newSolicitud = {
        ...data.reserva,
        nombreZona: zonasComunes.find(z => z.idZona === parseInt(formData.ID_zonaComun))?.descripcion || 'Desconocido'
      };

      setSolicitudes([...solicitudes, newSolicitud]);
      setModalVisible(false);
      Alert.alert('Éxito', 'Solicitud creada correctamente');
    } catch (err) {
      Alert.alert('Error', err instanceof Error ? err.message : 'Error desconocido');
    }
  };

  const updateSolicitud = async () => {
    if (!validateForm()) {
      Alert.alert('Error', 'Por favor complete todos los campos correctamente');
      return;
    }

    try {
      if (!currentSolicitud) return;

      // Primero eliminamos la solicitud existente
      const deleteResponse = await fetch('http://192.168.1.105:3001/api/cancelar-reserva', {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          ID_Apartamentooss: currentSolicitud.ID_Apartamentooss,
          ID_zonaComun: currentSolicitud.ID_zonaComun,
          fechainicio: currentSolicitud.fechainicio.split('T')[0],
          Hora_inicio: currentSolicitud.Hora_inicio
        }),
      });

      if (!deleteResponse.ok) {
        const errorData = await deleteResponse.json();
        throw new Error(errorData.error || 'Error al actualizar la solicitud');
      }

      const createResponse = await fetch('http://192.168.1.105:3001/api/reservar-zona', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          ID_Apartamentooss: formData.ID_Apartamentooss,
          ID_zonaComun: parseInt(formData.ID_zonaComun),
          fechainicio: formData.fechainicio,
          fechafinal: formData.fechafinal,
          Hora_inicio: formData.Hora_inicio,
          Hora_final: formData.Hora_final
        }),
      });

      const data = await createResponse.json();

      if (!createResponse.ok) {
        throw new Error(data.error || 'Error al actualizar la solicitud');
      }


      const updatedSolicitudes = solicitudes.filter(s => 
        !(s.ID_Apartamentooss === currentSolicitud.ID_Apartamentooss && 
          s.ID_zonaComun === currentSolicitud.ID_zonaComun && 
          s.fechainicio === currentSolicitud.fechainicio &&
          s.Hora_inicio === currentSolicitud.Hora_inicio)
      );

      const updatedSolicitud = {
        ...data.reserva,
        nombreZona: zonasComunes.find(z => z.idZona === parseInt(formData.ID_zonaComun))?.descripcion || 'Desconocido'
      };

      setSolicitudes([...updatedSolicitudes, updatedSolicitud]);
      setEditModalVisible(false);
      Alert.alert('Éxito', 'Solicitud actualizada correctamente');
    } catch (err) {
      Alert.alert('Error', err instanceof Error ? err.message : 'Error desconocido');
    }
  };

  const deleteSolicitud = async (solicitud: SolicitudZona) => {
    try {
      Alert.alert(
        'Confirmar eliminación',
        '¿Estás seguro de que quieres eliminar esta solicitud?',
        [
          {
            text: 'Cancelar',
            style: 'cancel'
          },
          {
            text: 'Eliminar',
            onPress: async () => {
              const response = await fetch('http://192.168.1.105:3001/api/cancelar-reserva', {
                method: 'DELETE',
                headers: {
                  'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                  ID_Apartamentooss: solicitud.ID_Apartamentooss,
                  ID_zonaComun: solicitud.ID_zonaComun,
                  fechainicio: solicitud.fechainicio.split('T')[0],
                  Hora_inicio: solicitud.Hora_inicio
                }),
              });

              if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || 'Error al eliminar la solicitud');
              }

              // Actualizar la lista de solicitudes
              const updatedSolicitudes = solicitudes.filter(s => 
                !(s.ID_Apartamentooss === solicitud.ID_Apartamentooss && 
                  s.ID_zonaComun === solicitud.ID_zonaComun && 
                  s.fechainicio === solicitud.fechainicio &&
                  s.Hora_inicio === solicitud.Hora_inicio)
              );

              setSolicitudes(updatedSolicitudes);
              Alert.alert('Éxito', 'Solicitud eliminada correctamente');
            }
          }
        ]
      );
    } catch (err) {
      Alert.alert('Error', err instanceof Error ? err.message : 'Error desconocido');
    }
  };

  const renderSolicitudItem = ({ item }: { item: SolicitudZona }) => (
    <View style={[styles.solicitudItem, styles[`estado${item.estado}`]]}>
      <View style={styles.solicitudHeader}>
        <Text style={styles.solicitudTitle}>Apartamento: {item.ID_Apartamentooss}</Text>
        <Text style={styles.solicitudText}>{item.estado}</Text>
      </View>
      <Text style={styles.solicitudText}>Zona: {item.nombreZona}</Text>
      <Text style={styles.solicitudText}>Fecha: {item.fechainicio.split('T')[0]} - {item.fechafinal.split('T')[0]}</Text>
      <Text style={styles.solicitudText}>Hora: {item.Hora_inicio} - {item.Hora_final}</Text>
      
      <View style={styles.actionsContainer}>
        <TouchableOpacity 
          style={[styles.actionButton, styles.editButton]}
          onPress={() => openEditForm(item)}
          disabled={item.estado !== 'PENDIENTE'}
        >
          <Text style={styles.actionText}>Editar</Text>
        </TouchableOpacity>
        <TouchableOpacity 
          style={[styles.actionButton, styles.deleteButton]}
          onPress={() => deleteSolicitud(item)}
          disabled={item.estado !== 'PENDIENTE'}
        >
          <Text style={styles.actionText}>Eliminar</Text>
        </TouchableOpacity>
      </View>
    </View>
  );

  return (
    <SafeAreaView style={styles.safeArea}>
      <ScrollView style={styles.container} contentContainerStyle={styles.scrollContent}>
        <View style={styles.header}>
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

        <View style={styles.header}>
          <TouchableOpacity onPress={() => navigation.goBack()}>
            <FontAwesome name="arrow-left" size={24} color="#0b4705" />
          </TouchableOpacity>
          <Text style={styles.title}> Zonas Comunes Mis reservas</Text>
        </View>

        <View style={styles.filterContainer}>
          <Text style={styles.filterTitle}>Filtrar por fecha:</Text>
          <Calendar
            onDayPress={handleDateChange}
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
              <Picker.Item key={zona.idZona} label={`${zona.descripcion} ($${zona.costo_alquiler})`} value={zona.idZona} />
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

            <TouchableOpacity
              style={styles.addButton}
              onPress={openNewForm}
            >
              <Text style={styles.addButtonText}>Nueva Solicitud</Text>
            </TouchableOpacity>
          </>
        )}
      </ScrollView>


      <Modal
        animationType="slide"
        transparent={true}
        visible={modalVisible}
        onRequestClose={() => setModalVisible(false)}
      >
        <View style={styles.modalContainer}>
          <View style={styles.modalContent}>
            <Text style={styles.modalTitle}>Nueva Solicitud</Text>
            
            <Text style={styles.label}>Número de Apartamento:</Text>
            <TextInput
              style={[styles.input, errors.ID_Apartamentooss ? styles.errorInput : null]}
              value={formData.ID_Apartamentooss}
              onChangeText={(text) => handleInputChange('ID_Apartamentooss', text)}
              placeholder="Ejemplo: 101, 202A, etc."
              keyboardType="default"
            />
            {errors.ID_Apartamentooss ? <Text style={styles.errorText}>{errors.ID_Apartamentooss}</Text> : null}

            <Text style={styles.label}>Zona Común:</Text>
            <Picker
              selectedValue={formData.ID_zonaComun}
              onValueChange={(itemValue) => handleInputChange('ID_zonaComun', itemValue)}
              style={[styles.picker, errors.ID_zonaComun ? styles.errorInput : null]}
            >
              <Picker.Item label="Seleccione una zona" value="" />
              {zonasComunes.map(zona => (
                <Picker.Item key={zona.idZona} label={`${zona.descripcion} ($${zona.costo_alquiler})`} value={zona.idZona.toString()} />
              ))}
            </Picker>
            {errors.ID_zonaComun ? <Text style={styles.errorText}>{errors.ID_zonaComun}</Text> : null}

            <Text style={styles.label}>Fecha de inicio:</Text>
            <TouchableOpacity 
              style={[styles.dateInput, errors.fechainicio ? styles.errorInput : null]}
              onPress={() => showPicker('date', 'fechainicio')}
            >
              <Text>{formData.fechainicio || 'Seleccionar fecha'}</Text>
            </TouchableOpacity>
            {errors.fechainicio ? <Text style={styles.errorText}>{errors.fechainicio}</Text> : null}

            <Text style={styles.label}>Fecha de finalización:</Text>
            <TouchableOpacity 
              style={[styles.dateInput, errors.fechafinal ? styles.errorInput : null]}
              onPress={() => showPicker('date', 'fechafinal')}
            >
              <Text>{formData.fechafinal || 'Seleccionar fecha'}</Text>
            </TouchableOpacity>
            {errors.fechafinal ? <Text style={styles.errorText}>{errors.fechafinal}</Text> : null}

            <Text style={styles.label}>Hora de inicio:</Text>
            <TouchableOpacity 
              style={[styles.dateInput, errors.Hora_inicio ? styles.errorInput : null]}
              onPress={() => showPicker('time', 'Hora_inicio')}
            >
              <Text>{formData.Hora_inicio || 'Seleccionar hora'}</Text>
            </TouchableOpacity>
            {errors.Hora_inicio ? <Text style={styles.errorText}>{errors.Hora_inicio}</Text> : null}

            <Text style={styles.label}>Hora de finalización:</Text>
            <TouchableOpacity 
              style={[styles.dateInput, errors.Hora_final ? styles.errorInput : null]}
              onPress={() => showPicker('time', 'Hora_final')}
            >
              <Text>{formData.Hora_final || 'Seleccionar hora'}</Text>
            </TouchableOpacity>
            {errors.Hora_final ? <Text style={styles.errorText}>{errors.Hora_final}</Text> : null}

            <View style={styles.modalButtons}>
              <TouchableOpacity
                style={[styles.modalButton, styles.cancelButton]}
                onPress={() => setModalVisible(false)}
              >
                <Text style={styles.modalButtonText}>Cancelar</Text>
              </TouchableOpacity>
              <TouchableOpacity
                style={[styles.modalButton, styles.submitButton]}
                onPress={submitSolicitud}
              >
                <Text style={styles.modalButtonText}>Guardar</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>


      <Modal
        animationType="slide"
        transparent={true}
        visible={editModalVisible}
        onRequestClose={() => setEditModalVisible(false)}
      >
        <View style={styles.modalContainer}>
          <View style={styles.modalContent}>
            <Text style={styles.modalTitle}>Editar Solicitud</Text>
            
            <Text style={styles.label}>Apartamento:</Text>
            <TextInput
              style={[styles.input, {backgroundColor: '#EEE'}]}
              value={formData.ID_Apartamentooss}
              editable={false}
            />

            <Text style={styles.label}>Zona Común:</Text>
            <Picker
              selectedValue={formData.ID_zonaComun}
              onValueChange={(itemValue) => handleInputChange('ID_zonaComun', itemValue)}
              style={[styles.picker, errors.ID_zonaComun ? styles.errorInput : null]}
            >
              {zonasComunes.map(zona => (
                <Picker.Item key={zona.idZona} label={`${zona.descripcion} ($${zona.costo_alquiler})`} value={zona.idZona.toString()} />
              ))}
            </Picker>
            {errors.ID_zonaComun ? <Text style={styles.errorText}>{errors.ID_zonaComun}</Text> : null}

            <Text style={styles.label}>Fecha de inicio:</Text>
            <TouchableOpacity 
              style={[styles.dateInput, errors.fechainicio ? styles.errorInput : null]}
              onPress={() => showPicker('date', 'fechainicio')}
            >
              <Text>{formData.fechainicio || 'Seleccionar fecha'}</Text>
            </TouchableOpacity>
            {errors.fechainicio ? <Text style={styles.errorText}>{errors.fechainicio}</Text> : null}

            <Text style={styles.label}>Fecha de finalización:</Text>
            <TouchableOpacity 
              style={[styles.dateInput, errors.fechafinal ? styles.errorInput : null]}
              onPress={() => showPicker('date', 'fechafinal')}
            >
              <Text>{formData.fechafinal || 'Seleccionar fecha'}</Text>
            </TouchableOpacity>
            {errors.fechafinal ? <Text style={styles.errorText}>{errors.fechafinal}</Text> : null}

            <Text style={styles.label}>Hora de inicio:</Text>
            <TouchableOpacity 
              style={[styles.dateInput, errors.Hora_inicio ? styles.errorInput : null]}
              onPress={() => showPicker('time', 'Hora_inicio')}
            >
              <Text>{formData.Hora_inicio || 'Seleccionar hora'}</Text>
            </TouchableOpacity>
            {errors.Hora_inicio ? <Text style={styles.errorText}>{errors.Hora_inicio}</Text> : null}

            <Text style={styles.label}>Hora de finalización:</Text>
            <TouchableOpacity 
              style={[styles.dateInput, errors.Hora_final ? styles.errorInput : null]}
              onPress={() => showPicker('time', 'Hora_final')}
            >
              <Text>{formData.Hora_final || 'Seleccionar hora'}</Text>
            </TouchableOpacity>
            {errors.Hora_final ? <Text style={styles.errorText}>{errors.Hora_final}</Text> : null}

            <View style={styles.modalButtons}>
              <TouchableOpacity
                style={[styles.modalButton, styles.cancelButton]}
                onPress={() => setEditModalVisible(false)}
              >
                <Text style={styles.modalButtonText}>Cancelar</Text>
              </TouchableOpacity>
              <TouchableOpacity
                style={[styles.modalButton, styles.submitButton]}
                onPress={updateSolicitud}
              >
                <Text style={styles.modalButtonText}>Actualizar</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>


      {showDatePicker && (
        <DateTimePicker
          value={new Date()}
          mode={pickerMode}
          display="default"
          onChange={handlePickerChange}
        />
      )}

      {showTimePicker && (
        <DateTimePicker
          value={new Date()}
          mode={pickerMode}
          display="default"
          onChange={handlePickerChange}
        />
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
    fontSize: 17,
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
    backgroundColor: '#041a05',
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
    borderLeftColor: '#4CAF50',
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
  editButton: {
    backgroundColor: '#FFC107',
  },
  deleteButton: {
    backgroundColor: '#F44336',
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
    marginLeft: 11,
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
  addButton: {
    backgroundColor: '#083004',
    padding: 15,
    borderRadius: 8,
    alignItems: 'center',
    marginTop: 20,
  },
  addButtonText: {
    color: '#FFF',
    fontWeight: 'bold',
    fontSize: 16,
  },
  // Modal styles
  modalContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: 'rgba(0,0,0,0.5)',
  },
  modalContent: {
    width: '90%',
    backgroundColor: '#FFF',
    borderRadius: 10,
    padding: 20,
  },
  modalTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#083004',
    marginBottom: 20,
    textAlign: 'center',
  },
  label: {
    fontSize: 14,
    color: '#083004',
    marginBottom: 5,
  },
  dateInput: {
    borderWidth: 1,
    borderColor: '#CCC',
    borderRadius: 5,
    padding: 10,
    marginBottom: 15,
  },
  modalButtons: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginTop: 20,
  },
  modalButton: {
    padding: 10,
    borderRadius: 5,
    alignItems: 'center',
    flex: 1,
    marginHorizontal: 5,
  },
  cancelButton: {
    backgroundColor: '#470604',
  },
  submitButton: {
    backgroundColor: '#083004',
  },
  modalButtonText: {
    color: '#FFF',
    fontWeight: 'bold',
  },
  errorInput:{

  },
  input:{

  }
});

export default ZonasComunes;