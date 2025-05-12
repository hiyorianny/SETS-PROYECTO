import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, ScrollView, SafeAreaView, ActivityIndicator, Image, Dimensions } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { useNavigation } from '@react-navigation/native';
import { useAuth } from '../../components/context/AuthContext';
import { FontAwesome } from '@expo/vector-icons';

type TorreData = {
  [piso: string]: {
    numApartamento: string;
  }[];
};

type TorresResponse = {
  torres: {
    [torre: string]: TorreData;
  };
  torresList: string[];
};

const { height } = Dimensions.get('window');

const Torres = () => {
  const { user, logout } = useAuth();
  const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
  const [loading, setLoading] = useState(true);
  const [torresData, setTorresData] = useState<{ [key: string]: TorreData }>({});
  const [torresList, setTorresList] = useState<string[]>([]);
  const [currentTorreIndex, setCurrentTorreIndex] = useState(0);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchTorres = async () => {
      try {
        const response = await fetch('http://192.168.1.105:3001/api/torres');
        if (!response.ok) {
          throw new Error('Error al obtener datos de torres');
        }
        const data: TorresResponse = await response.json();
        setTorresData(data.torres);
        setTorresList(data.torresList);
      } catch (err) {
        console.error('Error fetching torres:', err);

      } finally {
        setLoading(false);
      }
    };

    fetchTorres();
  }, []);

  const cambiarTorre = (direccion: number) => {
    let newIndex = currentTorreIndex + direccion;

    if (newIndex < 0) {
      newIndex = torresList.length - 1;
    } else if (newIndex >= torresList.length) {
      newIndex = 0;
    }

    setCurrentTorreIndex(newIndex);
  };

  const renderPiso = (piso: string, apartamentos: { numApartamento: string }[]) => {
    return (
      <View key={piso} style={styles.pisoContainer}>
        <View style={styles.pisoHeader}>
          <Text style={styles.pisoTitle}>Piso: {piso}</Text>
        </View>
        <Text style={styles.apartamentosTitle}>Apartamentos:</Text>
        <View style={styles.apartamentosContainer}>
          {apartamentos.map((apto, index) => (
            <View key={index} style={styles.apartamentoCard}>
              <Text style={styles.apartamentoText}>
                Número: {apto.numApartamento}
              </Text>
            </View>
          ))}
        </View>
      </View>
    );
  };

  if (loading) {
    return (
      <SafeAreaView style={styles.safeArea}>
        <ActivityIndicator size="large" color="#1e871e" style={styles.loader} />
      </SafeAreaView>
    );
  }

  if (error) {
    return (
      <SafeAreaView style={styles.safeArea}>
        <Text style={styles.errorText}>{error}</Text>
      </SafeAreaView>
    );
  }

  if (torresList.length === 0) {
    return (
      <SafeAreaView style={styles.safeArea}>
        <Text style={styles.noDataText}>No hay datos de torres disponibles</Text>
      </SafeAreaView>
    );
  }

  const currentTorre = torresList[currentTorreIndex];
  const pisosData = torresData[currentTorre] || {};

  return (
    <SafeAreaView style={styles.safeArea}>

      <View style={styles.header}>

      </View>
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
          <FontAwesome name="bell" size={28} color="#19800f" />
          <View style={styles.notificationBadge} />
        </TouchableOpacity>
      </View>

      <View style={styles.contentContainer}>
        <ScrollView
          style={styles.scrollContainer}
          contentContainerStyle={styles.scrollContent}
        >

          <View style={styles.pageHeader}>
            <TouchableOpacity onPress={() => navigation.goBack()}>
              <FontAwesome name="arrow-left" size={24} color="#19800f" />
            </TouchableOpacity>
            <Text style={styles.headerTitle}>Torres y Apartamentos</Text>
            <View style={{ width: 24 }} />
          </View>


          <View style={styles.torreControls}>
            <TouchableOpacity
              style={styles.torreButton}
              onPress={() => cambiarTorre(-1)}
            >
              <FontAwesome name="arrow-left" size={24} color="#fff" />
              <Text style={styles.torreButtonText}>Anterior</Text>
            </TouchableOpacity>

            <Text style={styles.currentTorre}>Torre {currentTorre}</Text>

            <TouchableOpacity
              style={styles.torreButton}
              onPress={() => cambiarTorre(1)}
            >
              <Text style={styles.torreButtonText}>Siguiente</Text>
              <FontAwesome name="arrow-right" size={24} color="#fff" />
            </TouchableOpacity>
          </View>
          {Object.entries(pisosData)
            .sort(([pisoA], [pisoB]) => pisoA.localeCompare(pisoB))
            .map(([piso, apartamentos]) => renderPiso(piso, apartamentos))}

          <View style={styles.footer}>
            <TouchableOpacity
              style={styles.actionButton}
              onPress={() => navigation.navigate('IngresoPeatonal')}
            >
              <Text style={styles.actionButtonText}>Ingreso Peatonal</Text>
            </TouchableOpacity>
          </View>
          <View style={styles.footer}>
            <TouchableOpacity
              style={styles.actionButton}
              onPress={() => navigation.navigate('IngresoPeatonalguarda')}
            >
              <Text style={styles.actionButtonText}>Tabla  Peatonal</Text>
            </TouchableOpacity>
          </View>


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
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 20,
    paddingHorizontal: 22,
  },
  mainHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 6,
    backgroundColor: '#083b10',
    borderBottomWidth: 1,
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
    marginVertical: 25,
  },
  headerTitle: {
    fontSize: 22,
    fontWeight: 'bold',
    color: '#083b10',
    textAlign: 'center',
    flex: 1,
  },
  loader: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  errorText: {
    color: '#ff6b6b',
    textAlign: 'center',
    marginVertical: 20,
    fontSize: 16,
  },
  noDataText: {
    color: '#083b10',
    textAlign: 'center',
    marginVertical: 20,
    fontSize: 16,
  },
  torreControls: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 20,
    backgroundColor: '#083b10',
    padding: 15,
    borderRadius: 10,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  torreButton: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 8,
  },
  torreButtonText: {
    color: '#fff',
    fontWeight: '600',
    marginHorizontal: 5,
  },
  currentTorre: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#fff',
  },
  pisoContainer: {
    backgroundColor: '#fff',
    borderRadius: 10,
    marginBottom: 15,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
    padding: 15,
  },
  pisoHeader: {
    borderBottomWidth: 1,
    borderBottomColor: '#083b10',
    paddingBottom: 10,
    marginBottom: 10,
  },
  pisoTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#083b10',
  },
  apartamentosTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#083b10',
    marginBottom: 10,
  },
  apartamentosContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    justifyContent: 'space-between',
  },
  apartamentoCard: {
    width: '48%',
    backgroundColor: '#083b10',
    borderRadius: 8,
    padding: 12,
    marginBottom: 10,
    borderWidth: 1,
    borderColor: '#dee2e6',
  },
  apartamentoText: {
    fontSize: 14,
    color: '#fff',
  },
  userInfo: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  logo: {
    width: 50,
    height: 60,
    borderRadius: 25,
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
    top: 0,
    right: 0,
    width: 8,
    height: 8,
    borderRadius: 4,
    backgroundColor: '#083b10',
  },
  welcomeContainer: {
    marginTop: 10,
  },
  actionButton: {
    backgroundColor: '#1e871e',
    padding: 15,
    borderRadius: 10,
    alignItems: 'center',
    marginTop: 20,
  },
  actionButtonText: {
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
  footer: {
    marginBottom: 20,
  },
});

export default Torres;