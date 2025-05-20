import React, { useEffect } from 'react';
import { ActivityIndicator, View, StyleSheet } from 'react-native';
import { useAuth } from '../components/context/AuthContext';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../App';
import { useNavigation } from '@react-navigation/native';

export const AuthLoading = () => {
  const { user, isAuthenticated, loading } = useAuth();
  const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();

  useEffect(() => {
    if (!loading) {
      if (isAuthenticated && user) {
        // Redirigir según el rol del usuario
        const roleName = user.rol?.nombre?.toLowerCase() || '';
        
        if (roleName.includes('admin')) {
          navigation.replace('AdminPrincipal');
        } else if (roleName.includes('guarda de seguridad') || roleName.includes('segur')) {
          navigation.replace('GuardaPrincipal');
        } else if (roleName.includes('residente')) {
          navigation.replace('ResidentePrincipal');
        } else {
          navigation.replace('HomeScreen');
        }
      } else {
        navigation.replace('HomeScreen');
      }
    }
  }, [loading, isAuthenticated, user]);

  return (
    <View style={styles.container}>
      <ActivityIndicator size="large" />
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center'
  }
});