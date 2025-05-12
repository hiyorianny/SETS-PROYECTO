import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ScrollView, ActivityIndicator, RefreshControl, Alert } from 'react-native';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { useNavigation } from '@react-navigation/native';
import { useAuth } from '../../components/context/AuthContext';
import { FontAwesome } from '@expo/vector-icons';

type Pago = {
  idPagos: number;
  pagoPor: string;
  cantidad: number;
  mediopago: string;
  apart: string;
  fechaPago: string;
  estado: 'Pendiente' | 'Pagado' | 'Vencido';
  referenciaPago?: string;
  PrimerNombre?: string;
  PrimerApellido?: string;
};

const Pagosadmin = () => {
  const { user, logout } = useAuth();
  const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
  const [pagos, setPagos] = useState<Pago[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const fetchPagos = async () => {
    try {
      setRefreshing(true);
      const response = await fetch('http://192.168.1.105:3001/api/pagos');
      const data = await response.json();
      setPagos(data);
    } catch (error) {
      console.error('Error fetching pagos:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  useEffect(() => {
    fetchPagos();
  }, []);

  const handleDeletePago = async (idPagos: number) => {
    try {
      const response = await fetch(`http://192.168.1.105:3001/api/pagos/${idPagos}`, {
        method: 'DELETE',
      });

      if (response.ok) {
        setPagos(pagos.filter(pago => pago.idPagos !== idPagos));
        Alert.alert('Éxito', 'El pago ha sido eliminado correctamente');
      } else {
        throw new Error('Error al eliminar el pago');
      }
    } catch (error) {
      console.error('Error deleting pago:', error);
      Alert.alert('Error', 'No se pudo eliminar el pago');
    }
  };

  const confirmDelete = (idPagos: number) => {
    Alert.alert(
      'Confirmar eliminación',
      '¿Estás seguro de que deseas eliminar este pago?',
      [
        {
          text: 'Cancelar',
          style: 'cancel',
        },
        {
          text: 'Eliminar',
          onPress: () => handleDeletePago(idPagos),
          style: 'destructive',
        },
      ],
      { cancelable: true }
    );
  };

  const getEstadoColor = (estado: string) => {
    switch (estado) {
      case 'Pagado': return '#4CAF50';
      case 'Pendiente': return '#FFC107';
      case 'Vencido': return '#F44336';
      default: return '#9E9E9E';
    }
  };

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#1d4a1d" />
      </View>
    );
  }



  const updateEstadoPago = async (idPagos: number, nuevoEstado: 'Pendiente' | 'Pagado' | 'Vencido') => {
    try {

      const response = await fetch(`http://192.168.1.105:3001/api/pagos/${idPagos}`, { 
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ estado: nuevoEstado }),
      });
  
      if (response.ok) {
        setPagos(pagos.map(pago => 
          pago.idPagos === idPagos ? { ...pago, estado: nuevoEstado } : pago
        ));
        Alert.alert('Éxito', 'Estado del pago actualizado correctamente');
      } else {
        const errorData = await response.json();
        throw new Error(errorData.error || 'Error al actualizar el estado del pago');
      }
    } catch (error) {
      console.error('Error updating pago:', error);
      Alert.alert('No se pudo actualizar el estado del pago');

      fetchPagos();
    }
  };


  const showEstadoSelector = (idPagos: number, currentEstado: string) => {
    Alert.alert(
      'Cambiar estado',
      'Seleccione el nuevo estado:',
      [
        {
          text: 'Pendiente',
          onPress: () => updateEstadoPago(idPagos, 'Pendiente'),
          style: currentEstado === 'Pendiente' ? 'cancel' : 'default'
        },
        {
          text: 'Pagado',
          onPress: () => updateEstadoPago(idPagos, 'Pagado'),
          style: currentEstado === 'Pagado' ? 'cancel' : 'default'
        },
        {
          text: 'Vencido',
          onPress: () => updateEstadoPago(idPagos, 'Vencido'),
          style: currentEstado === 'Vencido' ? 'cancel' : 'default'
        },
        {
          text: 'Cancelar',
          style: 'cancel'
        }
      ]
    );
  };

  return (
    <View style={styles.container}>
      <ScrollView
        contentContainerStyle={styles.scrollContainer}
        refreshControl={
          <RefreshControl
            refreshing={refreshing}
            onRefresh={fetchPagos}
            colors={['#1d4a1d']}
            tintColor="#1d4a1d"
          />
        }
      >
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
              <Text style={styles.welcomeText}>Gestión de Pagos</Text>
            </View>
          </View>
          <TouchableOpacity
            style={styles.notificationIcon}
            onPress={() => navigation.navigate('Notificacionesadmin')}
          >
            <FontAwesome name="bell" size={28} color="#10520a" />
            <View style={styles.notificationBadge} />
          </TouchableOpacity>
        </View>

        <View style={styles.header}>
          <TouchableOpacity onPress={() => navigation.goBack()}>
            <FontAwesome name="arrow-left" size={24} color="#0b4705" />
          </TouchableOpacity>
          <Text style={styles.title}>Historial de Pagos</Text>
          <View style={{ width: 24 }} />
        </View>

        <View style={styles.table}>
          <View style={[styles.row, styles.headerRow]}>
            <Text style={[styles.cell, styles.headerCell, styles.firstCell]}>Tipo</Text>
            <Text style={[styles.cell, styles.headerCell]}>Apa</Text>

            <Text style={[styles.cell, styles.headerCell]}>Fecha</Text>
            <Text style={[styles.cell, styles.headerCell]}>Estado</Text>
            <Text style={[styles.cell, styles.headerCell, styles.lastCell]}></Text>
          </View>

          {pagos.length > 0 ? (
            pagos.map((pago) => (
              <View key={pago.idPagos} style={styles.row}>
                <TouchableOpacity
                  style={[styles.cellTouchable, styles.firstCell]}
                  onPress={() => navigation.navigate('DetallePago', {
                    pago: {
                      idPagos: pago.idPagos,
                      pagoPor: pago.pagoPor,
                      cantidad: pago.cantidad,
                      mediopago: pago.mediopago,
                      apart: pago.apart,
                      fechaPago: pago.fechaPago,
                      estado: pago.estado,
                      referenciaPago: pago.referenciaPago,
                      PrimerNombre: pago.PrimerNombre,
                      PrimerApellido: pago.PrimerApellido
                    }
                  })}
                >
                  <Text style={styles.cellText}>{pago.pagoPor}</Text>
                </TouchableOpacity>
                <Text style={styles.cell}>{pago.apart}</Text>

                <Text style={styles.cell}>{new Date(pago.fechaPago).toLocaleDateString()}</Text>
                <TouchableOpacity 
                style={styles.cell}
                onPress={() => showEstadoSelector(pago.idPagos, pago.estado)}
              >
                <Text style={[
                  { color: getEstadoColor(pago.estado), fontWeight: 'bold' }
                ]}>
                  {pago.estado} ▼
                </Text>
              </TouchableOpacity>
                <TouchableOpacity
                  style={[styles.cell, styles.lastCell, styles.deleteButton]}
                  onPress={() => confirmDelete(pago.idPagos)}
                >
                  <FontAwesome name="trash" size={20} color="#F44336" />
                </TouchableOpacity>
              </View>
            ))
          ) : (
            <View style={styles.noData}>
              <Text>No hay pagos registrados</Text>
            </View>
          )}
        </View>
        <TouchableOpacity 
        style={styles.insertButton}
        onPress={() => navigation.navigate('NuevoPago')}
      >
        <Text style={styles.insertButtonText}>Insertar un pago</Text>
      </TouchableOpacity>

   
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
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  scrollContainer: {
    padding: 16,
    paddingBottom: 80,
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 20,
  },
  userInfo: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  logo: {
    width: 50,
    height: 60,
    borderRadius: 50,
    marginRight: 46,
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
    backgroundColor: '#072b0d',
  },
  title: {
    fontSize: 22,
    fontWeight: 'bold',
    color: '#072b0d',
  },
  Text: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#fff',
  },
  table: {
    borderWidth: 1,
    borderColor: '#072b0d',
    borderRadius: 8,
    overflow: 'hidden',
    marginBottom: 20,
  },
  row: {
    flexDirection: 'row',
    borderBottomWidth: 1,
    borderColor: '#072b0d',
    backgroundColor: '#fff',
    alignItems: 'center',
  },
  headerRow: {
    backgroundColor: '#1d4a1d',
  },
  cell: {
    flex: 1,
    padding: 4,
    textAlign: 'center',
    justifyContent: 'center',
    fontSize: 14,
  },
  cellTouchable: {
    flex: 1,
    padding: 7,
    justifyContent: 'center',
  },
  cellText: {
    textAlign: 'center',
    fontSize: 11,
  },
  firstCell: {
    flex: 3,
    textAlign: 'left',
  },
  lastCell: {
    flex: 0.8,
  },
  headerCell: {
    color: '#fff',
    fontWeight: 'bold',
  },
  deleteButton: {
    padding: 5,
    alignItems: 'center',
    justifyContent: 'center',
  },
  resumenContainer: {
    backgroundColor: '#072b0d',
    borderRadius: 8,
    padding: 16,
    marginBottom: 20,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 2,
  },
  resumenTitle: {
    fontSize: 15,
    fontWeight: 'bold',
    marginBottom: 12,
    color: '#fff',
  },
  resumenItem: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 8,
  },
  resumenMonto: {
    fontWeight: 'bold',
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f5f5f5',
  },
  noData: {
    padding: 20,
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#fff',
  },
  bottomNav: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    alignItems: 'center',
    backgroundColor: '#091f09',
    borderTopWidth: 1,
    borderTopColor: '#eee',
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
    fontWeight: '900'
  },
  welcomeContainer: {
    marginTop: 10,
  },
  insertButton: {
    backgroundColor: '#1d4a1d',
    padding: 15,
    borderRadius: 8,
    alignItems: 'center',
    marginTop: 10,
    marginBottom: 20,
  },
  insertButtonText: {
    color: 'white',
    fontWeight: 'bold',
    fontSize: 16,
  },
});

export default Pagosadmin;