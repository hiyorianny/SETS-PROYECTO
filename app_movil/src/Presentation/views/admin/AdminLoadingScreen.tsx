import React, { useEffect } from 'react';
import { View, StyleSheet, ActivityIndicator, Image, Text } from 'react-native';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { useNavigation } from '@react-navigation/native';
import { useAuth } from '../../components/context/AuthContext';


export const AdminLoadingScreen: React.FC = () => {
  const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
  const { user, logout } = useAuth();


  useEffect(() => {
    const timer = setTimeout(() => {
      navigation.replace('AdminPrincipal');
    }, 2000);

    return () => clearTimeout(timer);
  }, [navigation]);

  return (
    <View style={styles.container}>
      <Image
        source={require('./img/ajustes.png')}
        style={styles.logo}
      />
      <ActivityIndicator size="large" color="#097329" />
      <Text style={styles.loadingText}>
        Verificando credenciales de seguridad...
      </Text>
    </View>
  );
};



const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#e6ffea',
  },
  logo: {
    width: 300,
    height: 300,
    resizeMode: 'contain',
    marginBottom: 30,
  },
  loadingText: {
    marginTop: 20,
    fontSize: 16,
    color: '#08290e',
    fontWeight: 'bold',
  },
});