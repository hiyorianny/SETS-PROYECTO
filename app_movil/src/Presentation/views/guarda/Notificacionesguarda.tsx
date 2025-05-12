import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, ScrollView, SafeAreaView, Image, ActivityIndicator } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { useNavigation } from '@react-navigation/native';
import { useAuth } from '../../components/context/AuthContext';
import { FontAwesome } from '@expo/vector-icons';

type Notificacion = {
  id: string;
  titulo: string;
  mensaje: string;
  fecha: string;
  leida: boolean;
  tipo: 'urgente' | 'informativa' | 'recordatorio';
  origen: 'anuncio' | 'ingreso' | 'contacto' | 'cita' | 'parqueadero' | 'zona_comun';
  originalId?: number;
};


const generateUniqueId = (prefix: string, originalId: number): string => {
  return `${prefix}-${originalId}`;
};

const Notificacionesguarda = () => {
  const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
  const [notificaciones, setNotificaciones] = useState<Notificacion[]>([]);
  const [nuevasNotificaciones, setNuevasNotificaciones] = useState(0);
  const [cargando, setCargando] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const { user, logout } = useAuth();

  const BASE_URL = 'http://192.168.1.105:3001';

  const cargarNotificaciones = async () => {
    try {
      setCargando(true);
      setError(null);
  
      const LIMITE = 5;
      const endpoints = [
        { url: '/api/anuncios', tipo: 'anuncio', prefix: 'anc' },
        { url: '/api/ingresos', tipo: 'ingreso', prefix: 'ing' },
        { url: '/api/solicitudes-zonas', tipo: 'zona_comun', prefix: 'zona' }
      ];
  
      const todasNotificaciones: Notificacion[] = [];
  
      const responses = await Promise.all(
        endpoints.map(endpoint =>
          fetch(`${BASE_URL}${endpoint.url}`)
            .then(res => res.json())
            .then(data => ({ data, tipo: endpoint.tipo, prefix: endpoint.prefix }))
            .catch(err => {
              console.error(`Error al cargar ${endpoint.tipo}:`, err);
              return { data: [], tipo: endpoint.tipo, prefix: endpoint.prefix };
            })
        ));
  
      for (const { data, tipo, prefix } of responses) {
        if (!Array.isArray(data)) continue;
  
        const limitData = data.slice(0, LIMITE);
  
        switch (tipo) {
          case 'anuncio':
            limitData.forEach((anuncio: any) => {
              if (!anuncio.idAnuncio) return;
              todasNotificaciones.push({
                id: generateUniqueId(prefix, anuncio.idAnuncio),
                titulo: anuncio.titulo || 'Nuevo anuncio',
                mensaje: anuncio.descripcion || 'Sin detalles',
                fecha: `${anuncio.fechaPublicacion} ${anuncio.horaPublicacion}`,
                leida: false,
                tipo: 'informativa',
                origen: 'anuncio',
                originalId: anuncio.idAnuncio
              });
            });
            break;
  
          case 'ingreso':
            limitData.forEach((ingreso: any) => {
              if (!ingreso.idIngreso_Peatonal) return;
              todasNotificaciones.push({
                id: generateUniqueId(prefix, ingreso.idIngreso_Peatonal),
                titulo: 'Nuevo ingreso registrado',
                mensaje: `Ingreso de ${ingreso.personasIngreso} personas`,
                fecha: ingreso.horaFecha,
                leida: false,
                tipo: 'urgente',
                origen: 'ingreso',
                originalId: ingreso.idIngreso_Peatonal
              });
            });
            break;
  
          case 'zona_comun':
            limitData.forEach((solicitud: any) => {
              if (!solicitud.ID_zonaComun || !solicitud.fechainicio) return;
              todasNotificaciones.push({
                id: generateUniqueId(prefix, solicitud.ID_zonaComun),
                titulo: 'Solicitud de zona común',
                mensaje: `Solicitud para ${solicitud.descripcion || 'zona común'}`,
                fecha: solicitud.fechainicio,
                leida: false,
                tipo: 'informativa',
                origen: 'zona_comun',
                originalId: solicitud.ID_zonaComun
              });
            });
            break;
        }
      }
  
      const notificacionesOrdenadas = todasNotificaciones.sort((a, b) =>
        new Date(b.fecha).getTime() - new Date(a.fecha).getTime()
      ).slice(0, 20);
  
      const nuevas = notificacionesOrdenadas.filter(notif => !notif.leida).length;
  
      setNotificaciones(notificacionesOrdenadas);
      setNuevasNotificaciones(nuevas);
  
    } catch (error) {
      console.error('Error al cargar notificaciones:', error);
      setError(error instanceof Error ? error.message : 'Error desconocido');
    } finally {
      setCargando(false);
    }
  };

  useEffect(() => {
    cargarNotificaciones();


    const intervalo = setInterval(cargarNotificaciones, 60000);
    return () => clearInterval(intervalo);
  }, []);

  const marcarComoLeida = (id: string) => {
    setNotificaciones(prev =>
      prev.map(notif => notif.id === id ? { ...notif, leida: true } : notif)
    );
    setNuevasNotificaciones(prev => Math.max(0, prev - 1));
  };

  const eliminarNotificacion = (id: string) => {
    setNotificaciones(prev => prev.filter(notif => notif.id !== id));

    const notifEliminada = notificaciones.find(n => n.id === id);
    if (notifEliminada && !notifEliminada.leida) {
      setNuevasNotificaciones(prev => Math.max(0, prev - 1));
    }
  };

  const NotificacionItem = ({ item }: { item: Notificacion }) => {
    const fechaFormateada = new Date(item.fecha).toLocaleString('es-ES', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });

    return (
      <View style={[
        styles.notificacionItem,
        item.leida ? styles.leida : styles.noLeida,
        item.tipo === 'urgente' && styles.urgente
      ]}>
        <View style={styles.notificacionHeader}>
          <Text style={styles.notificacionTitulo}>
            {item.titulo}
            {item.tipo === 'urgente' && (
              <Text style={styles.urgenteBadge}> URGENTE</Text>
            )}
          </Text>
          <Text style={styles.notificacionFecha}>{fechaFormateada}</Text>
        </View>
        <Text style={styles.notificacionMensaje}>{item.mensaje}</Text>
        <Text style={styles.notificacionOrigen}>Tipo: {item.origen}</Text>
        <View style={styles.notificacionActions}>
          {!item.leida && (
            <TouchableOpacity
              style={styles.actionButton}
              onPress={() => marcarComoLeida(item.id)}
            >
              <Ionicons name="checkmark-done" size={12} color="#fff" />
              <Text style={styles.actionButtonText}> Marcar como leída</Text>
            </TouchableOpacity>
          )}
          <TouchableOpacity
            style={[styles.actionButton, styles.deleteButton]}
            onPress={() => eliminarNotificacion(item.id)}
          >
            <Ionicons name="trash" size={16} color="#fff" />
            <Text style={styles.actionButtonText}> Eliminar</Text>
          </TouchableOpacity>
        </View>
      </View>
    );
  };

  if (cargando) {
    return (
      <SafeAreaView style={styles.safeArea}>
        <View style={styles.loadingContainer}>
          <ActivityIndicator size="large" color="#1e871e" />
          <Text style={styles.loadingText}>Cargando notificaciones...</Text>
        </View>
      </SafeAreaView>
    );
  }

  if (error) {
    return (
      <SafeAreaView style={styles.safeArea}>
        <View style={styles.errorContainer}>
          <Ionicons name="warning-outline" size={50} color="#ff5252" />
          <Text style={styles.errorText}>Error al cargar notificaciones</Text>
          <Text style={styles.errorDetail}>{error}</Text>
          <TouchableOpacity
            style={styles.retryButton}
            onPress={cargarNotificaciones}
          >
            <Text style={styles.retryButtonText}>Reintentar</Text>
          </TouchableOpacity>
        </View>
      </SafeAreaView>
    );
  }

  return (
    <SafeAreaView style={styles.safeArea}>

      <View style={styles.mainHeader}>

      </View>
      <View style={styles.mainHeader}>
        <View style={styles.userInfo}>
          <Image
            source={require('./img/guarda.png')}
            style={styles.logo}
          />
          <View style={styles.welcomeContainer}>
            <Text style={styles.userName}>Guarda de seguridad</Text>
            <Text style={styles.welcomeText}>
              {user ? `${user.Usuario} ` : 'Usuario'}
            </Text>
          </View>
        </View>
        <TouchableOpacity
          style={styles.notificationIcon}
          onPress={() => navigation.navigate('Notificacionesguarda')}
        >
          <FontAwesome name="bell" size={24} color="#1d4a1d" />
          {nuevasNotificaciones > 0 && (
            <View style={styles.notificationBadge}>
              <Text style={{ color: 'white', fontSize: 10 }}>{nuevasNotificaciones}</Text>
            </View>
          )}
        </TouchableOpacity>
      </View>

      <View style={styles.contentContainer}>
        <ScrollView
          style={styles.scrollContainer}
          contentContainerStyle={styles.scrollContent}
        >
          <View style={styles.pageHeader}>
            <TouchableOpacity onPress={() => navigation.goBack()}>
              <FontAwesome name="arrow-left" size={24} color="#1e871e" />
            </TouchableOpacity>
            <Text style={styles.headerTitle}>Notificaciones</Text>
            <View style={{ width: 24 }} />
          </View>

          <View style={styles.filters}>
            <TouchableOpacity
              style={styles.filterButton}

            >
              <Text style={styles.filterButtonText}>Todas</Text>
            </TouchableOpacity>

          </View>

          {notificaciones.length === 0 ? (
            <View style={styles.emptyContainer}>
              <Image
                source={require('./img/alerta.png')}
                style={styles.emptyImage}
              />
              <Text style={styles.emptyText}>No hay notificaciones</Text>
            </View>
          ) : (
            notificaciones.map(notif => (
              <NotificacionItem
                key={`${notif.id}-${notif.leida}`}
                item={notif}
              />
            ))
          )}

          <View style={{ height: 80 }} />
        </ScrollView>
      </View>


      <View style={styles.bottomNav}>
        <TouchableOpacity
          style={styles.navItem}
          onPress={() => navigation.navigate('GuardaPrincipal')}
        >
          <FontAwesome name="home" size={24} color="#fff" />
          <Text style={styles.navText}>Inicio</Text>
        </TouchableOpacity>



        <TouchableOpacity
          style={styles.navItem}
          onPress={() => navigation.navigate('guardaperfil')}
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
    backgroundColor: '#f5f5f5',
  },
  mainHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 16,
    backgroundColor: '#fff',
    borderBottomWidth: 1,
    borderBottomColor: '#eee',
  },
  contentContainer: {
    flex: 1,
    marginBottom: 60,
  },
  scrollContainer: {
    flex: 1,
  },
  scrollContent: {
    paddingHorizontal: 16,
    paddingBottom: 20,
  },
  pageHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginVertical: 20,
  },
  headerTitle: {
    fontSize: 22,
    fontWeight: 'bold',
    color: '#1e871e',
    textAlign: 'center',
    flex: 1,
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
    fontSize: 15,
    fontWeight: '900',
    color: '#0d330d',
  },
  notificationIcon: {
    position: 'relative',
  },
  notificationBadge: {
    position: 'absolute',
    top: -5,
    right: -5,
    width: 18,
    height: 18,
    borderRadius: 9,
    backgroundColor: '#FF5252',
    justifyContent: 'center',
    alignItems: 'center',
  },
  welcomeContainer: {
    marginTop: 10,
  },
  filters: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    marginBottom: 20,
  },
  filterButton: {
    paddingVertical: 8,
    paddingHorizontal: 16,
    borderRadius: 20,
    backgroundColor: '#e8f5e9',
  },
  filterButtonText: {
    color: '#1e871e',
    fontWeight: '600',
  },
  notificacionItem: {
    backgroundColor: '#fff',
    borderRadius: 10,
    padding: 15,
    marginBottom: 15,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  leida: {
    opacity: 0.8,
    borderLeftWidth: 0,
  },
  noLeida: {
    borderLeftWidth: 6,
    borderLeftColor: '#1e871e',
  },
  urgente: {
    borderLeftColor: '#ff5252',
  },
  notificacionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 10,
  },
  notificacionTitulo: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#0d330d',
    flex: 1,
  },
  notificacionFecha: {
    fontSize: 12,
    color: '#666',
  },
  notificacionMensaje: {
    fontSize: 14,
    color: '#333',
    marginBottom: 15,
  },
  notificacionActions: {
    flexDirection: 'row',
    justifyContent: 'flex-end',
  },
  actionButton: {
    backgroundColor: '#1e871e',
    paddingVertical: 6,
    paddingHorizontal: 10,
    borderRadius: 5,
    marginLeft: 5,
  },
  deleteButton: {
    backgroundColor: '#ff5252',
  },
  actionButtonText: {
    color: '#fff',
    fontSize: 12,
  },
  emptyContainer: {
    alignItems: 'center',
    justifyContent: 'center',
    paddingVertical: 50,
  },
  emptyImage: {
    width: 100,
    height: 100,
    marginBottom: 20,
  },
  emptyText: {
    fontSize: 16,
    color: '#666',
  },
  urgenteBadge: {
    color: '#ff5252',
    fontWeight: 'bold',
    fontSize: 12,
    marginLeft: 5
  },
  notificacionOrigen: {
    fontSize: 12,
    color: '#666',
    fontStyle: 'italic',
    marginBottom: 10
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  loadingText: {
    marginTop: 20,
    fontSize: 16,
    color: '#666',
  },
  errorContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: 20,
  },
  errorText: {
    fontSize: 20,
    color: '#ff5252',
    marginTop: 10,
    fontWeight: 'bold',
  },
  errorDetail: {
    fontSize: 14,
    color: '#666',
    marginTop: 10,
    textAlign: 'center',
  },
  retryButton: {
    marginTop: 20,
    backgroundColor: '#1e871e',
    paddingVertical: 10,
    paddingHorizontal: 20,
    borderRadius: 5,
  },
  retryButtonText: {
    color: 'white',
    fontSize: 16,
  },
  bottomNav: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    alignItems: 'center',
    backgroundColor: '#091f09',
    paddingVertical: 10,
    height: 60,
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
    color: '#fff',
    marginTop: 4,
    fontWeight: '900'
  },
});
export default Notificacionesguarda;