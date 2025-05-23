import React, { useState, useEffect } from 'react';
import { Picker } from '@react-native-picker/picker';
import { View, Text, Image, TextInput, ToastAndroid, ScrollView, TouchableOpacity, Alert, ActivityIndicator } from 'react-native';
import { RoundedButton } from '../../components/RoundedButton';
import { RootStackParamList } from '../../../../App';
import { useNavigation } from '@react-navigation/native';
import { StackNavigationProp } from '@react-navigation/stack';
import styles from './Styles';
import { MyColors } from '../../theme/AppTheme';
import { useAuth } from '../../components/context/AuthContext';

interface Role {
    id: number;
    Roldescripcion: string;
}

export const RegisterScreen = () => {
    const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
    const { login } = useAuth();
    const [roles, setRoles] = useState<Role[]>([]);
    const [loadingRoles, setLoadingRoles] = useState(true);
    const [errors, setErrors] = useState<Record<string, string>>({});
    
    const [userData, setUserData] = useState({
        idRol: '', 
        PrimerNombre: '',
        SegundoNombre: '',
        PrimerApellido: '',
        SegundoApellido: '',
        Correo: '',
        Id_tipoDocumento: '', 
        numeroDocumento: '',
        telefonoUno: '',
        telefonoDos: '',
        tipo_propietario: '', 
        apartamento: '',
        Usuario: '',
        Clave: '',
        confirmPassword: ''
    });

    useEffect(() => {
        const fetchRoles = async () => {
            try {
                const response = await fetch('http://192.168.1.100:3001/api/auth/roles');
                const data = await response.json();
                
                if (response.ok && data.success) {
                    setRoles(data.roles);
                } else {
                    setRoles([
                        { id: 1111, Roldescripcion: 'Admin' },
                        { id: 2222, Roldescripcion: 'Guarda de Seguridad' },
                        { id: 3333, Roldescripcion: 'Residente' },
                    ]);
                    ToastAndroid.show('Error al cargar roles. Usando valores predeterminados', ToastAndroid.LONG);
                }
            } catch (error) {
                setRoles([
                    { id: 1111, Roldescripcion: 'Admin' },
                    { id: 2222, Roldescripcion: 'Guarda de Seguridad' },
                    { id: 3333, Roldescripcion: 'Residente' },
                ]);
                ToastAndroid.show('Error de conexión. Usando valores predeterminados', ToastAndroid.LONG);
            } finally {
                setLoadingRoles(false);
            }
        };
        
        fetchRoles();
    }, []);

    const validateField = (name: string, value: string): string | null => {
        switch (name) {
            case "PrimerNombre":
            case "SegundoNombre":
            case "PrimerApellido":
            case "SegundoApellido":
                if (!/^[a-zA-Z\s]*$/.test(value)) {
                    return "Solo se deben colocar letras.";
                }
                if (value.trim().length === 0) {
                    return "Este campo es obligatorio";
                }
                return null;
            
            case "Correo":
                if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|org|net|edu|gov|co|mx|ar|cl|es)$/i.test(value)) {
                    return "El correo debe ser válido";
                }
                return null;
            
            case "numeroDocumento":
                if (!/^\d{10,}$/.test(value)) {
                    return "El número  al menos 10 dígitos.";
                }
                return null;
            
            case "telefonoUno":
            case "telefonoDos":
                if (name === "telefonoUno" && !value) {
                    return "Este campo es obligatorio debe tener  10 dígitos";
                }
                if (value && !/^\d{10}$/.test(value)) {
                    return "El teléfono debe tener  10 dígitos.";
                }
                return null;
            
            case "Clave":
                if (value.length < 8 || value.length > 17) {
                    return " Entre 8 y 17 caracteres.";
                }
                return null;
            
            case "confirmPassword":
                if (value !== userData.Clave) {
                    return "Las contraseñas no coinciden.";
                }
                return null;
            
            default:
                return null;
        }
    };

    const handleChange = (name: string, value: string) => {
        // Validar el campo mientras el usuario escribe
        const error = validateField(name, value);
        if (error) {
            setErrors({ ...errors, [name]: error });
        } else {
            const newErrors = { ...errors };
            delete newErrors[name];
            setErrors(newErrors);
        }
        
        setUserData({ ...userData, [name]: value });
    };

    const validateForm = (): boolean => {
        const baseRequiredFields = [
            'idRol', 'PrimerNombre', 'PrimerApellido', 'Correo', 
            'Id_tipoDocumento', 'numeroDocumento', 'telefonoUno',
            'Usuario', 'Clave', 'confirmPassword'
        ];
        
        // Solo agregar estos campos si no es Guarda de Seguridad
        const additionalRequiredFields = userData.idRol !== '2222' 
            ? ['tipo_propietario', 'apartamento'] 
            : [];
        
        const requiredFields = [...baseRequiredFields, ...additionalRequiredFields];
        
        const newErrors: Record<string, string> = {};
        let isValid = true;
        
        // Validar campos requeridos
        requiredFields.forEach(field => {
            if (!userData[field as keyof typeof userData]) {
                newErrors[field] = "Este campo es obligatorio";
                isValid = false;
            }
        });
        
        // Validar campos con reglas específicas
        Object.keys(userData).forEach(key => {
            // No validar tipo_propietario y apartamento si el rol es guarda
            if (userData.idRol === '2222' && (key === 'tipo_propietario' || key === 'apartamento')) {
                return;
            }
            
            const value = userData[key as keyof typeof userData];
            const error = validateField(key, value || '');
            if (error) {
                newErrors[key] = error;
                isValid = false;
            }
        });
        
        setErrors(newErrors);
        return isValid;
    };

    const handleRegister = async () => {
        if (!validateForm()) {
            Alert.alert('Error', 'Por favor corrija los errores en el formulario');
            return;
        }
    
        try {
            const requestData = {
                ...userData,
                numeroDocumento: Number(userData.numeroDocumento),
                telefonoUno: Number(userData.telefonoUno),
                telefonoDos: userData.telefonoDos ? Number(userData.telefonoDos) : null,
                // Cambiar esto para enviar null en lugar de cadena vacía
                tipo_propietario: userData.idRol === '2222' ? null : userData.tipo_propietario,
                apartamento: userData.idRol === '2222' ? null : userData.apartamento
            };
    
            const response = await fetch('http://192.168.1.100:3001/api/auth/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestData),
            });
    
            const data = await response.json();
    
            if (response.ok) {
                await login(data.user, data.token);
                
                switch(userData.idRol) {
                    case '1111': // Admin
                        navigation.replace('registeradminloading');
                        break;
                    case '2222': // Guarda de Seguridad
                        navigation.replace('guardaregistroloading');
                        break;
                    case '3333': // Residente
                        navigation.replace('registerloadinresidente');
                        break;
                    default:
                        navigation.replace('HomeScreen');
                }
                ToastAndroid.show('Registro exitoso!', ToastAndroid.LONG);
            } else {
                Alert.alert('Error', data.error || 'Error en el registro');
            }
        } catch (error) {
            console.error('Error en registro:', error);
            Alert.alert('Error', 'No se pudo conectar al servidor');
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
                <Text style={styles.formTitle}>REGISTRARSE</Text>
                <ScrollView contentContainerStyle={styles.scrollContainer}>

                    <View style={styles.formInput}>
                        <Image style={styles.formIcon} source={require('../../../../assets/recursos-humanos.png')} />
                        {loadingRoles ? (
                            <View style={styles.loadingContainer}>
                                <ActivityIndicator size="small" color={MyColors.primary} />
                                <Text style={styles.loadingText}>Cargando roles...</Text>
                            </View>
                        ) : (
                            <Picker
                                style={styles.formTextInput}
                                selectedValue={userData.idRol}
                                onValueChange={(value) => handleChange('idRol', value)}
                            >
                                <Picker.Item label="Seleccione un rol..." value="" />
                                {roles.map((role) => (
                                    <Picker.Item 
                                        key={role.id} 
                                        label={role.Roldescripcion} 
                                        value={role.id.toString()} 
                                    />
                                ))}
                            </Picker>
                        )}
                        {errors.idRol && <Text style={styles.errorText}>{errors.idRol}</Text>}
                    </View>

                    <View style={styles.formInput}>
                        <Image style={styles.formIcon} source={require('../../../../assets/nuevo.png')} />
                        <TextInput
                            style={styles.formTextInput}
                            placeholder='Primer Nombre*'
                            keyboardType='default'
                            value={userData.PrimerNombre}
                            onChangeText={(text) => handleChange('PrimerNombre', text)}
                        />
                        {errors.PrimerNombre && <Text style={styles.errorText}>{errors.PrimerNombre}</Text>}
                    </View>

                    <View style={styles.formInput}>
                        <Image style={styles.formIcon} source={require('../../../../assets/nuevo.png')} />
                        <TextInput
                            style={styles.formTextInput}
                            placeholder='Segundo Nombre'
                            keyboardType='default'
                            value={userData.SegundoNombre}
                            onChangeText={(text) => handleChange('SegundoNombre', text)}
                        />
                        {errors.SegundoNombre && <Text style={styles.errorText}>{errors.SegundoNombre}</Text>}
                    </View>

                    <View style={styles.formInput}>
                        <Image style={styles.formIcon} source={require('../../../../assets/nuevo.png')} />
                        <TextInput
                            style={styles.formTextInput}
                            placeholder='Primer Apellido*'
                            keyboardType='default'
                            value={userData.PrimerApellido}
                            onChangeText={(text) => handleChange('PrimerApellido', text)}
                        />
                        {errors.PrimerApellido && <Text style={styles.errorText}>{errors.PrimerApellido}</Text>}
                    </View>

                    <View style={styles.formInput}>
                        <Image style={styles.formIcon} source={require('../../../../assets/nuevo.png')} />
                        <TextInput
                            style={styles.formTextInput}
                            placeholder='Segundo Apellido'
                            keyboardType='default'
                            value={userData.SegundoApellido}
                            onChangeText={(text) => handleChange('SegundoApellido', text)}
                        />
                        {errors.SegundoApellido && <Text style={styles.errorText}>{errors.SegundoApellido}</Text>}
                    </View>

                    <View style={styles.formInput}>
                        <Image style={styles.formIcon} source={require('../../../../assets/email.png')} />
                        <TextInput
                            style={styles.formTextInput}
                            placeholder='Correo Electrónico*'
                            keyboardType='email-address'
                            autoCapitalize='none'
                            value={userData.Correo}
                            onChangeText={(text) => handleChange('Correo', text)}
                        />
                        {errors.Correo && <Text style={styles.errorText}>{errors.Correo}</Text>}
                    </View>

                    <View style={styles.formInput}>
                        <Image style={styles.formIcon} source={require('../../../../assets/nuevo.png')} />
                        <Picker
                            style={styles.formTextInput}
                            selectedValue={userData.Id_tipoDocumento}
                            onValueChange={(value) => handleChange('Id_tipoDocumento', value)}
                        >
                            <Picker.Item label="Seleccione tipo de documento..." value="" />
                            <Picker.Item label="Cedula de Ciudadanía" value="1" />
                            <Picker.Item label="Cédula de ciudadanía digital" value="2" />
                            <Picker.Item label="Cédulas de Extranjería" value="4" />
                        </Picker>
                        {errors.Id_tipoDocumento && <Text style={styles.errorText}>{errors.Id_tipoDocumento}</Text>}
                    </View>
                   
                    <View style={styles.formInput}>
                        <Image style={styles.formIcon} source={require('../../../../assets/nuevo.png')} />
                        <TextInput
                            style={styles.formTextInput}
                            placeholder='Número de Documento*'
                            keyboardType='numeric'
                            value={userData.numeroDocumento}
                            onChangeText={(text) => handleChange('numeroDocumento', text)}
                        />
                        {errors.numeroDocumento && <Text style={styles.errorText}>{errors.numeroDocumento}</Text>}
                    </View>

                    {/* Mostrar estos campos solo si no es Guarda de Seguridad */}
                    {userData.idRol !== '2222' && (
                        <>
                            <View style={styles.formInput}>
                                <Image style={styles.formIcon} source={require('../../../../assets/apartamento.png')} />
                                <Picker
                                    style={styles.formTextInput}
                                    selectedValue={userData.tipo_propietario}
                                    onValueChange={(value) => handleChange('tipo_propietario', value)}
                                >
                                    <Picker.Item label="Seleccione tipo de propietario..." value="" />
                                    <Picker.Item label="Dueño" value="dueño" />
                                    <Picker.Item label="Residente" value="residente" />
                                    <Picker.Item label="Ambos" value="ambos" />
                                    <Picker.Item label="ninguno" value="ninguno" />
                                </Picker>
                                {errors.tipo_propietario && <Text style={styles.errorText}>{errors.tipo_propietario}</Text>}
                            </View>

                            <View style={styles.formInput}>
                                <Image style={styles.formIcon} source={require('../../../../assets/apartamento.png')} />
                                <TextInput
                                    style={styles.formTextInput}
                                    placeholder='Apartamento*'
                                    keyboardType='default'
                                    value={userData.apartamento}
                                    onChangeText={(text) => handleChange('apartamento', text)}
                                />
                                {errors.apartamento && <Text style={styles.errorText}>{errors.apartamento}</Text>}
                            </View>
                        </>
                    )}

                    <View style={styles.formInput}>
                        <Image style={styles.formIcon} source={require('../../../../assets/llamada-telefonica.png')} />
                        <TextInput
                            style={styles.formTextInput}
                            placeholder='Teléfono 1*'
                            keyboardType='numeric'
                            value={userData.telefonoUno}
                            onChangeText={(text) => handleChange('telefonoUno', text)}
                        />
                        {errors.telefonoUno && <Text style={styles.errorText}>{errors.telefonoUno}</Text>}
                    </View>

                    <View style={styles.formInput}>
                        <Image style={styles.formIcon} source={require('../../../../assets/llamada-telefonica.png')} />
                        <TextInput
                            style={styles.formTextInput}
                            placeholder='Teléfono 2 (Opcional)'
                            keyboardType='numeric'
                            value={userData.telefonoDos}
                            onChangeText={(text) => handleChange('telefonoDos', text)}
                        />
                        {errors.telefonoDos && <Text style={styles.errorText}>{errors.telefonoDos}</Text>}
                    </View>

                    <View style={styles.formInput}>
                        <Image style={styles.formIcon} source={require('../../../../assets/nuevo.png')} />
                        <TextInput
                            style={styles.formTextInput}
                            placeholder='Usuario*'
                            keyboardType='default'
                            autoCapitalize='none'
                            value={userData.Usuario}
                            onChangeText={(text) => handleChange('Usuario', text)}
                        />
                        {errors.Usuario && <Text style={styles.errorText}>{errors.Usuario}</Text>}
                    </View>

                    <View style={styles.formInput}>
                        <Image style={styles.formIcon} source={require('../../../../assets/ddd.png')} />
                        <TextInput
                            style={styles.formTextInput}
                            placeholder='Contraseña*'
                            keyboardType='default'
                            secureTextEntry={true}
                            value={userData.Clave}
                            onChangeText={(text) => handleChange('Clave', text)}
                        />
                        {errors.Clave && <Text style={styles.errorText}>{errors.Clave}</Text>}
                    </View>

                    <View style={styles.formInput}>
                        <Image style={styles.formIcon} source={require('../../../../assets/ddd.png')} />
                        <TextInput
                            style={styles.formTextInput}
                            placeholder='Confirmar Contraseña*'
                            keyboardType='default'
                            secureTextEntry={true}
                            value={userData.confirmPassword}
                            onChangeText={(text) => handleChange('confirmPassword', text)}
                        />
                        {errors.confirmPassword && <Text style={styles.errorText}>{errors.confirmPassword}</Text>}
                    </View>

                    <View style={styles.buttonContainer}>
                        <RoundedButton
                            text='CONFIRMAR'
                            onPress={handleRegister}
                        />
                    </View>

                    <View style={styles.formFooter}>
                        <Text style={styles.footerText}>¿Ya tienes cuenta?</Text>
                        <TouchableOpacity onPress={() => navigation.navigate('HomeScreen')}>
                            <Text style={styles.footerLink}>Iniciar Sesión</Text>
                        </TouchableOpacity>
                        <TouchableOpacity style={{ marginTop: 10 }}>
                            <Text onPress={() => navigation.navigate('ForgotPasswordScreen')} style={styles.footerLink}>
                                Recuperar Contraseña
                            </Text>
                        </TouchableOpacity>
                    </View>
                </ScrollView>
            </View>
        </View>
    );
};