import React, { useState } from 'react';
import { View, Text, StyleSheet, TextInput, TouchableOpacity, ScrollView, SafeAreaView, Alert, ActivityIndicator, Image } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import DateTimePickerModal from 'react-native-modal-datetime-picker';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { useNavigation } from '@react-navigation/native';
import { useAuth } from '../../components/context/AuthContext';
import { FontAwesome } from '@expo/vector-icons';

type IngresoPeatonalProps = {
    navigation: StackNavigationProp<RootStackParamList, 'IngresoPeatonal'>;
};

const IngresoPeatonal: React.FC<IngresoPeatonalProps> = () => {
    const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
    const { user } = useAuth();
    const [formData, setFormData] = useState({
        tipo_ingreso: '',
        placa: '',
        personasIngreso: '',
        documento: '',
        horaFecha: new Date(),
    });
    const [isDatePickerVisible, setDatePickerVisibility] = useState(false);
    const [loading, setLoading] = useState(false);

    const handleChange = (name: string, value: string) => {
        setFormData({
            ...formData,
            [name]: value,
        });

        if (name === 'tipo_ingreso' && value !== 'vehiculo') {
            setFormData(prev => ({
                ...prev,
                placa: '',
            }));
        }
    };

    const showDatePicker = () => {
        setDatePickerVisibility(true);
    };

    const hideDatePicker = () => {
        setDatePickerVisibility(false);
    };

    const handleConfirm = (date: Date) => {
        setFormData({
            ...formData,
            horaFecha: date,
        });
        hideDatePicker();
    };

    const handleSubmit = async () => {
        if (!formData.tipo_ingreso || !formData.personasIngreso || !formData.documento) {
            Alert.alert('Error', 'Por favor complete todos los campos obligatorios');
            return;
        }

        if (formData.tipo_ingreso === 'vehiculo' && !formData.placa) {
            Alert.alert('Error', 'Por favor ingrese la placa del vehículo');
            return;
        }

        setLoading(true);

        try {
            const response = await fetch('http://192.168.1.105:3001/api/ingresos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    tipo_ingreso: formData.tipo_ingreso,
                    placa: formData.placa,
                    personasIngreso: formData.personasIngreso,
                    documento: formData.documento,
                    horaFecha: formData.horaFecha.toISOString(),
                }),
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || 'Error al registrar el ingreso');
            }

            Alert.alert('Éxito', 'Ingreso registrado correctamente');
            setFormData({
                tipo_ingreso: '',
                placa: '',
                personasIngreso: '',
                documento: '',
                horaFecha: new Date(),
            });
        } catch (error) {
            console.error('Error:', error);

        } finally {
            setLoading(false);
        }
    };

    const formatDate = (date: Date) => {
        return date.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    };

    return (
        <SafeAreaView style={styles.safeArea}>
            <ScrollView contentContainerStyle={styles.container}>
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

                <View style={styles.formContainer}>
                    <TouchableOpacity onPress={() => navigation.goBack()}>
                         <FontAwesome name="arrow-left" size={24} color="#1e871e" />
                    </TouchableOpacity>
                    <Text style={styles.sectionTitle}>Registrar Ingreso</Text>

                    <View style={styles.inputGroup}>
                        <Text style={styles.label}>Tipo de Ingreso *</Text>
                        <View style={styles.radioGroup}>
                            <TouchableOpacity
                                style={[
                                    styles.radioButton,
                                    formData.tipo_ingreso === 'vehiculo' && styles.radioButtonSelected,
                                ]}
                                onPress={() => handleChange('tipo_ingreso', 'vehiculo')}
                            >
                                <Text style={[
                                    styles.radioText,
                                    formData.tipo_ingreso === 'vehiculo' && styles.radioTextSelected,
                                ]}>
                                    Vehículo
                                </Text>
                            </TouchableOpacity>
                            <TouchableOpacity
                                style={[
                                    styles.radioButton,
                                    formData.tipo_ingreso === 'visitante' && styles.radioButtonSelected,
                                ]}
                                onPress={() => handleChange('tipo_ingreso', 'visitante')}
                            >
                                <Text style={[
                                    styles.radioText,
                                    formData.tipo_ingreso === 'visitante' && styles.radioTextSelected,
                                ]}>
                                    Visitante
                                </Text>
                            </TouchableOpacity>
                        </View>
                    </View>

                    {formData.tipo_ingreso === 'vehiculo' && (
                        <View style={styles.inputGroup}>
                            <Text style={styles.label}>Placa del Vehículo *</Text>
                            <TextInput
                                style={styles.input}
                                placeholder="Ej: ABC123"
                                value={formData.placa}
                                onChangeText={(text) => handleChange('placa', text)}
                            />
                        </View>
                    )}

                    <View style={styles.inputGroup}>
                        <Text style={styles.label}>Nombre de Persona *</Text>
                        <TextInput
                            style={styles.input}
                            placeholder="Nombre completo"
                            value={formData.personasIngreso}
                            onChangeText={(text) => handleChange('personasIngreso', text)}
                        />
                    </View>

                    <View style={styles.inputGroup}>
                        <Text style={styles.label}>Tipo y Número de Documento *</Text>
                        <TextInput
                            style={styles.input}
                            placeholder="Ej: CC 123456789"
                            value={formData.documento}
                            onChangeText={(text) => handleChange('documento', text)}
                        />
                    </View>

                    <View style={styles.inputGroup}>
                        <Text style={styles.label}>Fecha y Hora *</Text>
                        <TouchableOpacity
                            style={styles.dateInput}
                            onPress={showDatePicker}
                        >
                            <Text style={styles.dateText}>
                                {formatDate(formData.horaFecha)}
                            </Text>
                            <Ionicons name="calendar" size={20} color="#1e871e" />
                        </TouchableOpacity>
                    </View>

                    <DateTimePickerModal
                        isVisible={isDatePickerVisible}
                        mode="datetime"
                        onConfirm={handleConfirm}
                        onCancel={hideDatePicker}
                        date={formData.horaFecha}
                        minimumDate={new Date()}
                        locale="es_ES"

                        confirmTextIOS="Confirmar"
                        cancelTextIOS="Cancelar"
                    />

                    <TouchableOpacity
                        style={styles.submitButton}
                        onPress={handleSubmit}
                        disabled={loading}
                    >
                        {loading ? (
                            <ActivityIndicator color="#fff" />
                        ) : (
                            <Text style={styles.submitButtonText}>Registrar Ingreso</Text>
                        )}
                    </TouchableOpacity>
                </View>
            </ScrollView>

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
        padding: 16,
        backgroundColor: '#fff',
        borderBottomWidth: 1,
        borderBottomColor: '#eee',
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
        backgroundColor: '#FF5252',
    },
    welcomeContainer: {
        marginTop: 10,
    },
    container: {
        padding: 16,
        paddingBottom: 80,
    },
    formContainer: {
        backgroundColor: '#fff',
        borderRadius: 10,
        padding: 20,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 4,
        elevation: 3,
    },
    sectionTitle: {
        fontSize: 18,
        fontWeight: 'bold',
        color: '#1e871e',
        marginBottom: 20,
        textAlign: 'center',
    },
    inputGroup: {
        marginBottom: 20,
    },
    label: {
        fontSize: 16,
        fontWeight: '600',
        color: '#333',
        marginBottom: 8,
    },
    input: {
        borderWidth: 1,
        borderColor: '#ddd',
        borderRadius: 8,
        padding: 12,
        fontSize: 16,
    },
    radioGroup: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        marginTop: 8,
    },
    radioButton: {
        borderWidth: 1,
        borderColor: '#ddd',
        borderRadius: 8,
        padding: 12,
        flex: 1,
        marginHorizontal: 4,
        alignItems: 'center',
    },
    radioButtonSelected: {
        borderColor: '#1e871e',
        backgroundColor: '#f0f9f0',
    },
    radioText: {
        fontSize: 16,
        color: '#666',
    },
    radioTextSelected: {
        color: '#1e871e',
        fontWeight: '600',
    },
    dateInput: {
        borderWidth: 1,
        borderColor: '#ddd',
        borderRadius: 8,
        padding: 12,
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
    },
    dateText: {
        fontSize: 16,
        color: '#333',
    },
    submitButton: {
        backgroundColor: '#1e871e',
        borderRadius: 8,
        padding: 16,
        alignItems: 'center',
        marginTop: 20,
    },
    submitButtonText: {
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
});

export default IngresoPeatonal;
