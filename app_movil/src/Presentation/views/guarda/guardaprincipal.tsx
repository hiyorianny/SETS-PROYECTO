import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ScrollView, FlatList, Linking, SafeAreaView, ActivityIndicator, Alert } from 'react-native';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { useAuth } from '../../components/context/AuthContext';
import { FontAwesome } from '@expo/vector-icons';
import { useNavigation } from '@react-navigation/native';

type Anuncio = {
  idAnuncio: number;
  titulo: string;
  descripcion: string;
  fechaPublicacion: string;
  horaPublicacion: string;
  persona: number;
  apart: string;
  img_anuncio: string;
};

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

const GuardaPrincipal = () => {
  const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
  const [anuncios, setAnuncios] = useState<Anuncio[]>([]);
  const [loading, setLoading] = useState(true);
  const { user, logout } = useAuth();
  const [error, setError] = useState<string | null>(null);
  const [notificaciones, setNotificaciones] = useState<Notificacion[]>([]);
  const [nuevasNotificaciones, setNuevasNotificaciones] = useState(0);
  const [cargandoNotificaciones, setCargandoNotificaciones] = useState(false);
  const BASE_URL = 'http://192.168.1.105:3001';

  const generateUniqueId = (prefix: string, originalId: number): string => {
    return `${prefix}-${originalId}`;
  };

  useEffect(() => {
    const fetchAnuncios = async () => {
      try {

        const response = await fetch('http://192.168.1.105:3001/api/anuncios');
        if (!response.ok) {
          throw new Error('Error al obtener anuncios');
        }
        const data = await response.json();
        setAnuncios(data);
      } catch (err) {
        console.error('Error fetching anuncios:', err);
        setError('Error al cargar anuncios');
      } finally {
        setLoading(false);
      }
    };

    fetchAnuncios();
  }, []);

  const cargarNotificaciones = async () => {
    try {
      setCargandoNotificaciones(true);
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
        )
      );

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
      setCargandoNotificaciones(false);
    }
  };

  useEffect(() => {
    cargarNotificaciones();
    const intervalo = setInterval(cargarNotificaciones, 20000);
    return () => clearInterval(intervalo);
  }, []);

  const marcarComoLeida = (id: string) => {
    setNotificaciones(prev =>
      prev.map(notif => notif.id === id ? { ...notif, leida: true } : notif)
    );
    setNuevasNotificaciones(prev => Math.max(0, prev - 1));
  };

  const AnuncioItem = ({ item }: { item: Anuncio }) => (
    <View style={styles.noticiaItem}>
      <View style={styles.noticiaHeader}>
        
        <Text style={styles.noticiaTitulo}>{item.titulo}</Text>
        <Text style={styles.noticiaFecha}>
          {new Date(item.fechaPublicacion).toLocaleDateString()} - {item.horaPublicacion}
        </Text>
        <TouchableOpacity
          onPress={() => eliminarAnuncio(item.idAnuncio)}
          style={styles.deleteButton}
        >
          <FontAwesome name="trash" size={26} color="#ff6b6b" />
        </TouchableOpacity>
      </View>
      <Text style={styles.noticiaResumen}>{item.descripcion}</Text>
    </View>
  );

  const handleLogout = async () => {
    Alert.alert(
      'Cerrar sesión',
      '¿Estás seguro de que deseas cerrar sesión?',
      [
        { text: 'Cancelar', style: 'cancel' },
        {
          text: 'Cerrar sesión',
          onPress: async () => {
            try {
              await logout();
              navigation.replace('HomeScreen');
            } catch (error) {
              console.error('Error al cerrar sesión:', error);
            }
          }
        }
      ]
    );
  };

  const eliminarAnuncio = async (idAnuncio: number) => {
    Alert.alert(
      'Eliminar anuncio',
      '¿Estás seguro de que deseas eliminar este anuncio?',
      [
        { text: 'Cancelar', style: 'cancel' },
        {
          text: 'Eliminar',
          onPress: async () => {
            try {
              const response = await fetch(`http://192.168.1.105:3001/api/elanuncios/${idAnuncio}`, {
                method: 'DELETE'
              });

              if (!response.ok) {
                throw new Error('Error al eliminar el anuncio');
              }

              setAnuncios(prev => prev.filter(anuncio => anuncio.idAnuncio !== idAnuncio));
              Alert.alert('Éxito', 'Anuncio eliminado correctamente');
            } catch (error) {
              console.error('Error al eliminar anuncio:', error);
              Alert.alert('Error', 'No se pudo eliminar el anuncio');
            }
          }
        }
      ]
    );
  };



  return (
    <SafeAreaView style={styles.safeArea}>
      <ScrollView
        style={styles.container}
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContent}
      >
        <View style={styles.header}>
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

        <View style={styles.menuContainer}>
          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => navigation.navigate('Parqueaderoguarda')}
          >
            <Image
              source={require('./img/esta.png')}
              style={styles.menuIcon}
            />
            <Text style={styles.menuText}>Parqueaderos</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => navigation.navigate('Torres')}
          >
            <Image
              source={require('./img/apartamentos.png')}
              style={styles.menuIcon}
            />
            <Text style={styles.menuText}>Torres</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => navigation.navigate('Zonasguarda')}
          >
            <Image
              source={require('./img/personas.png')}
              style={styles.menuIcon}
            />
            <Text style={styles.menuText}>Zonas comunes</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => Linking.openURL('https://drive.google.com/file/d/1uEtHROICghEdsCqnssXrYiu202_HTX5m/view?usp=sharing')}
          >
            <Image
              source={require('./img/informacion.png')}
              style={styles.menuIcon}
            />
            <Text style={styles.menuText}>Manual de Convivencia</Text>
          </TouchableOpacity>
        </View>

        <View style={styles.noticiasSection}>
          <Text style={styles.sectionTitle}>🔔 ANUNCIOS 🔔</Text>
          {loading ? (
            <ActivityIndicator size="large" color="#ffff" />
          ) : error ? (
            <Text style={{ color: '#ff6b6b', textAlign: 'center', marginVertical: 20 }}>
              {error}
            </Text>
          ) : (
            <FlatList
              data={anuncios}
              renderItem={AnuncioItem}
              keyExtractor={item => item.idAnuncio.toString()}
              scrollEnabled={false}
            />
          )}
          <TouchableOpacity
            style={styles.actionButton}
            onPress={() => navigation.navigate('Anunciosguarda')}
          >
            <Text style={styles.asectionTitle}>Ingresar Anuncios</Text>
          </TouchableOpacity>
        </View>



        <View style={styles.footer}>
          <Text style={styles.footerText}>Versión 0.1.0</Text>
        </View>
      </ScrollView>

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

        <TouchableOpacity
          style={styles.navItem}
          onPress={handleLogout}
        >
          <FontAwesome name="sign-out" size={24} color="#ecf0f1" />
          <Text style={styles.navText}>Cerrar Sesión</Text>
        </TouchableOpacity>
      </View>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  safeArea: {
    flex: 2,
    backgroundColor: '#fff',
  },
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
    padding: 12,
    marginBottom: 60,
  },
  actionButton: {
    backgroundColor: '#1e871e',
    padding: 15,
    borderRadius: 10,
    alignItems: 'center',
    marginTop: 20,
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 30,
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
    backgroundColor: 'red',
    borderRadius: 10,
    width: 18,
    height: 18,
    justifyContent: 'center',
    alignItems: 'center',
  },
  menuContainer: {
    marginBottom: 30,
  },
  menuItem: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#fff',
    padding: 15,
    borderRadius: 10,
    marginBottom: 15,
    shadowColor: '#092b09',
    shadowOffset: { width: 0, height: 22 },
    shadowOpacity: 12.5,
    shadowRadius: 5,
    elevation: 6,
  },
  menuIcon: {
    width: 55,
    height: 55,
    marginRight: 15,
  },
  menuText: {
    fontSize: 16,
    color: '#092b09',
    fontWeight: '700',
  },
  noticiasSection: {
    marginBottom: 20,
  },
  asectionTitle: {
    fontSize: 16,
    color: '#fff',
    fontWeight: 'bold',
  },

  sectionTitle: {
    fontSize: 23,
    fontWeight: 'bold',
    color: '#092b05',
    marginBottom: 15,
    paddingLeft: 45,
    alignItems: 'center',
  },
  noticiaItem: {
    backgroundColor: '#fff',
    borderRadius: 10,
    padding: 15,
    marginBottom: 12,
    shadowColor: '#1e871e',
    shadowOffset: { width: 0, height: 6 },
    shadowOpacity: 1.1,
    shadowRadius: 5,
    elevation: 7,
  },
  noticiaHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 50,
  },
  noticiaTitulo: {
    fontSize: 16,
    fontWeight: '900',
    color: '#032109',
    flex: 1,
  },
  noticiaFecha: {
    fontSize: 10,
    color: '#1e871e',
  },
  noticiaResumen: {
    fontSize: 14,
    color: '#1e871e',
    marginBottom: 10,
  },
  footer: {
    alignItems: 'center',
    marginTop: 20,
  },
  footerText: {
    fontSize: 12,
    color: '#091f09',
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
  scrollContent: {
    padding: 20,
    paddingTop: 30,
  },
  welcomeContainer: {
    marginTop: 10,
  },
  deleteButton: {
    marginLeft: 23,
    padding: 1,
},
});

export default GuardaPrincipal;