import React, { useState, useEffect } from 'react';
import { View, Text, Image, ToastAndroid, TouchableOpacity, ActivityIndicator } from 'react-native';
import { RoundedButton } from '../../components/RoundedButton';
import { useNavigation } from '@react-navigation/native';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { CustomTextInput } from '../../components/CusatomTextInput';
import styles from './Styles';
import { useAuth } from '../../components/context/AuthContext';

interface Role {
  id: number;
  Roldescripcion: string;
}

export const HomeScreen = () => {
    const { login } = useAuth();
    const [credentials, setCredentials] = useState({
        Usuario: '',
        Clave: ''
    });
    const [roles, setRoles] = useState<Role[]>([]);
    const [loadingRoles, setLoadingRoles] = useState(true);
    const [loginLoading, setLoginLoading] = useState(false);
    const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();

    // Cargar roles al iniciar la pantalla
    useEffect(() => {
        const fetchRoles = async () => {
            try {
                const response = await fetch('http://192.168.1.105:3000/api/auth/roles');
                const data = await response.json();
                
                if (response.ok && data.success) {
                    setRoles(data.roles);
                } else {
                    console.error('Error al cargar roles:', data.error);
                    setRoles([
                        { id: 1111, Roldescripcion: 'admin' },
                        { id: 2222, Roldescripcion: 'Guarda de Seguridad' },
                        { id: 3333, Roldescripcion: 'residente' },
                    ]);
                    ToastAndroid.show('Error al cargar roles. Usando valores predeterminados', ToastAndroid.LONG);
                }
            } catch (error) {
                console.error('Error de conexión:', error);
                setRoles([
                    { id: 1111, Roldescripcion: 'admin' },
                    { id: 2222, Roldescripcion: 'Guarda de Seguridad' },
                    { id: 3333, Roldescripcion: 'residente' },
                ]);
                ToastAndroid.show('Error de conexión. Usando valores predeterminados', ToastAndroid.LONG);
            } finally {
                setLoadingRoles(false);
            }
        };
        
        fetchRoles();
    }, []);

    const onChange = (property: string, value: string) => {
        setCredentials({...credentials, [property]: value});
    }

    const handleLogin = async () => {
        if (credentials.Usuario === '' || credentials.Clave === '') {
            ToastAndroid.show('Usuario y contraseña son requeridos', ToastAndroid.SHORT);
            return;
        }
    
        setLoginLoading(true);
    
        try {
            const response = await fetch('http://192.168.1.105:3000/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(credentials)
            });
    
            const data = await response.json();
    
            if (!response.ok) {
                ToastAndroid.show(data.error || 'Error en el inicio de sesión', ToastAndroid.SHORT);
                return;
            }
            
            // Guardar datos de usuario y token
            await login(data.user, data.token);
            
            // Redirección según el rol
            const roleName = data.user?.rol?.nombre?.toLowerCase() || '';
            
            if (roleName.includes('admin')) {
                navigation.replace('AdminLoadingScreen');
            } 
            else if (roleName.includes('guarda') || roleName.includes('segur')) {
                navigation.replace('GuardaLoadingScreen');
            }
            else if (roleName.includes('residente')) {
                navigation.replace('ResidenteLoadingScreen');
            }
            else {
                ToastAndroid.show(`Rol no configurado: ${data.user?.rol?.nombre}`, ToastAndroid.SHORT);
            }
            
            ToastAndroid.show('Inicio de sesión exitoso', ToastAndroid.SHORT);
        } catch (error) {
            console.error('Error en inicio de sesión:', error);
            ToastAndroid.show('Error de conexión con el servidor', ToastAndroid.SHORT);
        } finally {
            setLoginLoading(false);
        }
    };

    return (
        <View style={styles.container}>
            <Image
                source={require('../../../../assets/img/A.jpg')}
                style={styles.imageBackground}
            />
            <View style={styles.logoContainer}>
                <Image
                    source={require('../../../../assets/img/c.png')}
                    style={styles.logoImage}
                />
                <Text style={styles.logoText}>SETS APP</Text>
            </View>
            <View style={styles.form}>
                <Text style={styles.formTitle}>INICIAR SESIÓN</Text>

                <CustomTextInput
                    image={require('../../../../assets/email.png')}
                    placeholder='Usuario'
                    keyboardType='default'
                    property='Usuario'
                    onChangeText={onChange}
                    value={credentials.Usuario}
                />

                <CustomTextInput
                    image={require('../../../../assets/seguro.png')}
                    placeholder='Contraseña'
                    keyboardType='default'
                    property='Clave'
                    onChangeText={onChange}
                    value={credentials.Clave}
                    secureTextEntry={true}
                />

                <View style={styles.buttonContainer}>
                    <RoundedButton
                        text={loginLoading ? 'CARGANDO...' : 'ENTRAR'}
                        onPress={handleLogin}
                    />
                </View>

                <View style={styles.formFooter}>
                    <Text style={styles.footerText}>¿No tienes cuenta?</Text>
                    <TouchableOpacity onPress={() => navigation.navigate('RegisterScreen')}>
                        <Text style={styles.footerLink}>Regístrarse</Text>
                    </TouchableOpacity>
                    <TouchableOpacity onPress={() => navigation.navigate('ForgotPasswordScreen')} style={{ marginTop: 10 }}>
                        <Text style={styles.footerLink}>Recuperar Contraseña</Text>
                    </TouchableOpacity>
                </View>
            </View>
        </View>
    );
};