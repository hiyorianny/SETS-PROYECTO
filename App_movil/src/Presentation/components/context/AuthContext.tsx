import React, { createContext, useContext, useState, useEffect } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';

interface Rol {
  id: number;
  nombre: string;
}

interface User {
  id_Registro: number;
  Usuario: string;
  PrimerNombre: string;
  SegundoNombre?: string;
  PrimerApellido: string;
  SegundoApellido?: string;
  Correo: string;
  telefonoUno?: number;
  telefonoDos?: number | null;
  apartamento?: string;
  tipo_propietario?: string;
  numeroDocumento?: number;
  Id_tipoDocumento?: string;
  imagenPerfil?: string;
  rol?: Rol;
}

interface AuthContextType {
  user: User | null;
  login: (userData: any, token: string) => Promise<void>;
  logout: () => Promise<void>;
  isAuthenticated: boolean;
  loading: boolean;
  updateUser: (userData: Partial<User>) => Promise<void>;
}

const AuthContext = createContext<AuthContextType>({} as AuthContextType);

export const AuthProvider = ({ children }: { children: React.ReactNode }) => {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);
  const [isAuthenticated, setIsAuthenticated] = useState(false);

  useEffect(() => {
    const loadUserFromStorage = async () => {
      try {
        const userData = await AsyncStorage.getItem('user');
        const token = await AsyncStorage.getItem('token');
        
        if (userData && token) {
          setUser(JSON.parse(userData));
          setIsAuthenticated(true);
        }
      } catch (error) {
        console.error('Error loading user from storage:', error);
      } finally {
        setLoading(false);
      }
    };

    loadUserFromStorage();
  }, []);

  const login = async (userData: any, token: string) => {
    try {
      const userToStore: User = {
        id_Registro: userData.id_Registro,
        Usuario: userData.Usuario,
        PrimerNombre: userData.PrimerNombre,
        SegundoNombre: userData.SegundoNombre,
        PrimerApellido: userData.PrimerApellido,
        SegundoApellido: userData.SegundoApellido,
        Correo: userData.Correo,
        telefonoUno: userData.telefonoUno,
        telefonoDos: userData.telefonoDos,
        apartamento: userData.apartamento,
        tipo_propietario: userData.tipo_propietario,
        numeroDocumento: userData.numeroDocumento,
        Id_tipoDocumento: userData.Id_tipoDocumento,
        imagenPerfil: userData.imagenPerfil,
        rol: userData.rol
      };

      await AsyncStorage.multiSet([
        ['user', JSON.stringify(userToStore)],
        ['token', token]
      ]);
      
      setUser(userToStore);
      setIsAuthenticated(true);
    } catch (error) {
      console.error('Error saving user data:', error);
      throw error;
    }
  };

  const logout = async () => {
    try {
      await AsyncStorage.multiRemove(['user', 'token']);
      setUser(null);
      setIsAuthenticated(false);
    } catch (error) {
      console.error('Error clearing storage:', error);
      throw error;
    }
  };

  const updateUser = async (userData: Partial<User>) => {
    try {
      if (!user) return;
      
      const updatedUser = { ...user, ...userData };
      await AsyncStorage.setItem('user', JSON.stringify(updatedUser));
      setUser(updatedUser);
    } catch (error) {
      console.error('Error updating user:', error);
      throw error;
    }
  };

  return (
    <AuthContext.Provider value={{ 
      user, 
      login, 
      logout, 
      isAuthenticated, 
      loading,
      updateUser
    }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);