import React, { useEffect } from 'react';
import { View, StyleSheet, ActivityIndicator, Image, Text } from 'react-native';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { useNavigation } from '@react-navigation/native';


export const registerloadinresidente: React.FC = () => {
  const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();

  useEffect(() => {
    const timer = setTimeout(() => {
      navigation.replace('residentebienvenido');
    }, 2000); 

    return () => clearTimeout(timer);
  }, [navigation]);

  return (
    <View style={styles.container}>
      <Image
           source={require('./img/resi.png')}
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