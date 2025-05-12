import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ScrollView, FlatList, SafeAreaView, ActivityIndicator } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { useNavigation } from '@react-navigation/native';
import { useAuth } from '../../components/context/AuthContext';
import { FontAwesome } from '@expo/vector-icons';

type Parqueadero = {
  id_Parqueadero: number;
  numero_parqueadero: number;
  disponibilidad: string;
  uso: string | null;
};

const Parqueaderoadmin = () => {
  const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
  const [parqueaderos, setParqueaderos] = useState<Parqueadero[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const { user } = useAuth();
  const [activeTab, setActiveTab] = useState<'carro' | 'moto'>('carro');

  useEffect(() => {
    fetchParqueaderos();
  }, []);

  const fetchParqueaderos = async () => {
    try {
      setLoading(true);
      const response = await fetch('http://192.168.1.105:3001/api/parqueaderos');
      if (!response.ok) throw new Error('Error al obtener parqueaderos');
      const data = await response.json();

      console.log('Datos recibidos:', JSON.stringify(data, null, 2));
      if (!Array.isArray(data)) {
        throw new Error('Formato de datos incorrecto');
      }

      setParqueaderos(data);
    } catch (err) {
      console.error('Error fetching parqueaderos:', err);
      setError('Error al cargar parqueaderos');
    } finally {
      setLoading(false);
    }
  };





  const renderParqueadero = ({ item }: { item: Parqueadero }) => {
    if (!item || typeof item !== 'object') {
      console.warn('Item de parqueadero inválido:', item);
      return null;
    }
    return (
      <View style={styles.parqueaderoCard}>

        <View style={styles.numberContainer}>
          <Text style={styles.parqueaderoNumber}>
            {item.numero_parqueadero?.toString() || 'N/D'}
          </Text>
        </View>

        <Image
          source={
            activeTab === 'carro'
              ? require('./img/esta.png')
              : require('./img/moto.png')
          }
          style={styles.parqueaderoImage}
        />

        <View style={[
          styles.statusBadge,
          item.disponibilidad === 'SI ESTA DISPONIBLE' ? styles.available : styles.reserved
        ]}>
          <Text style={styles.statusText}>{item.disponibilidad || 'Estado desconocido'}</Text>
        </View>

        <Text style={styles.availableText}>DISPONIBLE DESDE O A PARTIR DE:</Text>

        {item.uso ? (
          <Text style={styles.dateText}>
            {new Date(item.uso).toLocaleDateString()} - {new Date(item.uso).toLocaleTimeString()}
          </Text>
        ) : (
          <Text style={styles.dateText}>No disponible</Text>
        )}

      </View>
    );
  };

  return (
    <SafeAreaView style={styles.safeArea}>
      <ScrollView style={styles.container}>
        <View style={styles.header}>

        </View>
        <View style={styles.header}>
          <TouchableOpacity
            onPress={() => navigation.goBack()}
            style={styles.backButton}
          >
            <FontAwesome name="arrow-left" size={24} color="#0b4705" />
          </TouchableOpacity>

          <View style={styles.userInfo}>
            <Image
              source={require('./img/ajustes.png')}
              style={styles.logo}
            />
            <View style={styles.welcomeContainer}>
              <Text style={styles.userName}>Admin</Text>
              <Text style={styles.welcomeText}>
                {user ? `${user.Usuario}` : 'Usuario'}
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

        <Text style={styles.title}>Parqueadero</Text>

        <View style={styles.tabsContainer}>
          <TouchableOpacity
            style={[styles.tab, activeTab === 'carro' && styles.activeTab]}
            onPress={() => setActiveTab('carro')}
          >
            <Text style={styles.tabText}>Carro</Text>
          </TouchableOpacity>
          <TouchableOpacity
            style={[styles.tab, activeTab === 'moto' && styles.activeTab]}
            onPress={() => setActiveTab('moto')}
          >
            <Text style={styles.tabText}>Moto</Text>
          </TouchableOpacity>
        </View>

        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Parqueadero {activeTab === 'carro' ? 'Carro' : 'Moto'}</Text>
          <Text style={styles.sectionSubtitle}>Parqueadero Zona 1</Text>
        </View>

        {loading ? (
          <ActivityIndicator size="large" color="#0f420b" style={styles.loader} />
        ) : error ? (
          <Text style={styles.errorText}>{error}</Text>
        ) : (
          <FlatList
            data={parqueaderos}
            renderItem={renderParqueadero}
            keyExtractor={(item, index) => item?.id_Parqueadero?.toString() || index.toString()}
            numColumns={2}
            contentContainerStyle={styles.listContainer}
            scrollEnabled={false}
            ListEmptyComponent={
              <View style={styles.emptyContainer}>
                <Text style={styles.emptyText}>No hay parqueaderos disponibles</Text>
                <TouchableOpacity
                  style={styles.refreshButton}
                  onPress={fetchParqueaderos}
                >
                  <Text>Reintentar</Text>
                </TouchableOpacity>
              </View>
            }
          />
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
    padding: 15,
    marginBottom: 60,
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 20,
  },
  backButton: {
    padding: 8,
  },
  userInfo: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  logo: {
    width: 50,
    height: 50,
    borderRadius: 25,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#13660b',
    textAlign: 'center',
    marginBottom: 20,
  },
  welcomeText: {
    fontSize: 18,
    color: '#083004',
    fontWeight: 'bold',
  },
  userName: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#083004',
  },
  notificationIcon: {
    padding: 10,
  },
  notificationBadge: {
    position: 'absolute',
    top: 7,
    right: 5,
    width: 9,
    height: 9,
    borderRadius: 7,
    backgroundColor: '#13660b',
  },
  tabsContainer: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    marginBottom: 20,
  },
  tab: {
    padding: 10,
    borderBottomWidth: 2,
    borderBottomColor: 'transparent',
  },
  activeTab: {
    borderBottomColor: '#0f420b',
  },
  tabText: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#0f420b',
  },
  section: {
    marginBottom: 20,
    alignItems: 'center',
  },
  sectionTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#0f420b',
  },
  sectionSubtitle: {
    fontSize: 16,
    color: '#0f420b',
  },
  listContainer: {
    paddingHorizontal: 5,
    paddingBottom: 20,
  },
  parqueaderoCard: {
    flex: 1,
    margin: 5,
    padding: 15,
    backgroundColor: '#fff',
    borderRadius: 10,
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  numberContainer: {
    backgroundColor: '#0f420b',
    borderRadius: 20,
    width: 40,
    height: 40,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 10,
  },
  parqueaderoNumber: {
    color: 'white',
    fontSize: 18,
    fontWeight: 'bold',
  },
  parqueaderoImage: {
    width: 60,
    height: 60,
    marginBottom: 10,
  },
  statusBadge: {
    padding: 5,
    borderRadius: 5,
    marginBottom: 10,
    width: '100%',
    alignItems: 'center',
  },
  available: {
    backgroundColor: '#d4edda',
  },
  reserved: {
    backgroundColor: '#f8d7da',
  },
  statusText: {
    fontSize: 12,
    fontWeight: 'bold',
  },
  availableText: {
    fontSize: 12,
    fontWeight: 'bold',
    marginBottom: 5,
    textAlign: 'center',
    color: '#0f420b',
  },
  dateText: {
    fontSize: 12,
    marginBottom: 10,
    textAlign: 'center',
  },
  reservarBtn: {
    backgroundColor: '#28a745',
    padding: 8,
    borderRadius: 5,
    width: '100%',
    alignItems: 'center',
  },
  liberarBtn: {
    backgroundColor: '#dc3545',
    padding: 8,
    borderRadius: 5,
    width: '100%',
    alignItems: 'center',
  },
  btnText: {
    color: '#fff',
    fontWeight: 'bold',
  },
  loader: {
    marginVertical: 20,
  },
  errorText: {
    color: '#dc3545',
    textAlign: 'center',
    marginVertical: 20,
  },
  emptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 50,
  },
  emptyText: {
    fontSize: 16,
    color: '#666',
    textAlign: 'center',
  },
  refreshButton: {
    marginTop: 10,
    padding: 10,
    backgroundColor: '#e0e0e0',
    borderRadius: 5,
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
    fontSize: 12,
    color: '#ecf0f1',
    marginTop: 4,
  },
  welcomeContainer: {
    marginLeft: 10,
  },
});

export default Parqueaderoadmin;