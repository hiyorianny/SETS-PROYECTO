import React from 'react';
import { View, Text, StyleSheet, ScrollView, ImageBackground, TouchableOpacity } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { useNavigation, RouteProp } from '@react-navigation/native';
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

type DetallePagoProps = {
  route: RouteProp<RootStackParamList, 'DetallePago'>;
};

const DetallePago: React.FC<DetallePagoProps> = ({ route }) => {
  const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
  const { pago } = route.params;
    const { user, logout } = useAuth();

  const formatDate = (dateString: string) => {
    try {
      return new Date(dateString).toLocaleDateString();
    } catch (error) {
      console.error('Error formateando fecha:', error);
      return 'Fecha inválida';
    }
  };

  return (
    <ImageBackground 
      source={require('./img/paseo.jpg')} 
      style={styles.background}
      imageStyle={{ opacity: 0.9 }}
    >
      <ScrollView contentContainerStyle={styles.container}>
        

        <View style={styles.papyrusContainer}>
        <View style={styles.header}>
          <TouchableOpacity onPress={() => navigation.goBack()} style={styles.backButton}>
                       <FontAwesome name="arrow-left" size={24} color="#0b4705" />
          </TouchableOpacity>
          <Text style={styles.title}>Detalle del Pago</Text>
        </View>
          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Concepto:</Text>
            <Text style={styles.sectionText}>{pago.pagoPor || 'No especificado'}</Text>
          </View>

          <View style={styles.divider} />

          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Apartamento:</Text>
            <Text style={styles.sectionText}>{pago.apart || 'No especificado'}</Text>
          </View>

          <View style={styles.divider} />

          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Monto:</Text>
            <Text style={[styles.sectionText, styles.amountText]}>
              ${ pago.cantidad  }
            </Text>
          </View>

          <View style={styles.divider} />

          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Fecha:</Text>
            <Text style={styles.sectionText}>
              {formatDate(pago.fechaPago)}
            </Text>
          </View>

          <View style={styles.divider} />

          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Método de Pago:</Text>
            <Text style={styles.sectionText}>{pago.mediopago || 'No especificado'}</Text>
          </View>

          <View style={styles.divider} />

          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Estado:</Text>
            <Text style={[
              styles.sectionText, 
              pago.estado === 'Pagado' ? styles.paidStatus : 
              pago.estado === 'Pendiente' ? styles.pendingStatus : 
              styles.overdueStatus
            ]}>
              {pago.estado || 'No especificado'}
            </Text>
          </View>

          {pago.referenciaPago && (
            <>
              <View style={styles.divider} />
              <View style={styles.section}>
                <Text style={styles.sectionTitle}>Referencia:</Text>
                <Text style={styles.sectionText}>{pago.referenciaPago}</Text>
              </View>
            </>
          )}
        </View>
      </ScrollView>
    </ImageBackground>
  );
};


const styles = StyleSheet.create({
  background: {
    flex: 1,
    backgroundColor: '#f5f1e6',
  },
  container: {
    flexGrow: 1,
    padding: 20,
    paddingBottom: 80,
  },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 25,
    paddingTop: 10,
  },
  backButton: {
    marginRight: 15,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#026e13',
    fontFamily: 'serif',
  },
  papyrusContainer: {
    backgroundColor: 'rgba(255, 255, 255, 0.85)',
    borderRadius: 10,
    padding: 20,
    borderWidth: 1,
    borderColor: '#0a5916',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.25,
    shadowRadius: 3.84,
    elevation: 5,
  },
  section: {
    marginVertical: 10,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#0a5916',
    marginBottom: 5,
    fontFamily: 'serif',
  },
  sectionText: {
    fontSize: 16,
    color: '#0a5916',
    fontFamily: 'serif',
  },
  amountText: {
    fontSize: 20,
    fontWeight: 'bold',
  },
  divider: {
    height: 1,
    backgroundColor: '#d2b48c',
    marginVertical: 10,
  },
  paidStatus: {
    color: '#2e7d32',
    fontWeight: 'bold',
  },
  pendingStatus: {
    color: '#ff8f00',
    fontWeight: 'bold',
  },
  overdueStatus: {
    color: '#c62828',
    fontWeight: 'bold',
  },
  buttonContainer: {
    marginTop: 25,
    alignItems: 'center',
  },
  editButton: {
    backgroundColor: '#5d4037',
    paddingVertical: 12,
    paddingHorizontal: 30,
    borderRadius: 25,
    borderWidth: 1,
    borderColor: '#3e2723',
  },
  buttonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
    fontFamily: 'serif',
  },
});

export default DetallePago;