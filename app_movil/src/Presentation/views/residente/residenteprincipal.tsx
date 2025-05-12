import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ScrollView, Linking, FlatList, SafeAreaView, ActivityIndicator, Alert } from 'react-native';
import { Ionicons, MaterialIcons, MaterialCommunityIcons } from '@expo/vector-icons';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { useNavigation } from '@react-navigation/native';
import { useAuth } from '../../components/context/AuthContext';
import { FontAwesome } from '@expo/vector-icons';


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


const ResidentePrincipal = () => {
  const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
  const [anuncios, setAnuncios] = useState<Anuncio[]>([]);
  const [loading, setLoading] = useState(true);
  const { user, logout } = useAuth();
  const [error, setError] = useState<string | null>(null);

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

      } finally {
        setLoading(false);
      }
    };

    fetchAnuncios();
  }, []);

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


        <View style={styles.menuContainer}>
          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => navigation.navigate('Parqueaderoresidente')}
          >
            <Image
              source={require('./img/estacionamiento (1).png')}
              style={styles.menuIcon}
            />
            <Text style={styles.menuText}>Parqueadero</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => navigation.navigate('Citas')}
          >
            <Image
              source={require('./img/cita.png')}
              style={styles.menuIcon}
            />
            <Text style={styles.menuText}>Citas</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => navigation.navigate('ZonasComunes')}
          >
            <Image
              source={require('./img/eeeeeeeeeeeeeeee.png')}
              style={styles.menuIcon}
            />
            <Text style={styles.menuText}>Zonas comunes</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => Linking.openURL('https://drive.google.com/file/d/1uEtHROICghEdsCqnssXrYiu202_HTX5m/view?usp=sharing')}
          >
            <Image
              source={require('./img/manual.png')}
              style={styles.menuIcon}
            />
            <Text style={styles.menuText}>Manual de Convivencia</Text>
          </TouchableOpacity>


        </View>


        <View style={styles.noticiasSection}>

          <Text style={styles.sectionTitle}>🔔 ANUNCIOS  🔔 </Text>




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
            onPress={() => navigation.navigate('Anunciosresi')}
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

        <TouchableOpacity
          style={styles.navItem}
          onPress={handleLogout}

        >
          <FontAwesome name="sign-out" size={24} color="#ecf0f1" />
          <Text style={styles.navText}>Cerrar Session</Text>
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
  noticiaImportante: {
    borderLeftWidth: 14,
    borderLeftColor: '#1e871e',
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
  verMasBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    alignSelf: 'flex-end',
  },
  verMasText: {
    color: '#1e871e',
    fontSize: 14,
    marginRight: 5,
  },
  verTodasBtn: {
    marginTop: 10,
    alignItems: 'center',
    padding: 10,
  },
  verTodasText: {
    color: '#1e871e',
    fontWeight: 'bold',
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
    fontWeight: 900
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
  asectionTitle: {
    fontSize: 16,
    color: '#fff',
    fontWeight: 'bold',
  },
  actionButton: {
    backgroundColor: '#1e871e',
    padding: 15,
    borderRadius: 10,
    alignItems: 'center',
    marginTop: 20,
  },
});

export default ResidentePrincipal;