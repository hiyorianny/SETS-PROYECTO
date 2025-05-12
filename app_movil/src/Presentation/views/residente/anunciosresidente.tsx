import React, { useState } from 'react';
import { View, Text, TextInput, TouchableOpacity, StyleSheet, Alert, Image, ScrollView, Platform } from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { useAuth } from '../../components/context/AuthContext';
import { FontAwesome } from '@expo/vector-icons';
import DateTimePicker from '@react-native-community/datetimepicker';

const Anunciosresi = () => {
    const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
    const { user, logout } = useAuth();
    const BASE_URL = 'http://192.168.1.105:3001';

    const [formData, setFormData] = useState({
        titulo: '',
        descripcion: '',
        img_anuncio: '',
        apart: user?.apartamento || '',
        fechaPublicacion: new Date().toISOString().split('T')[0],
        horaPublicacion: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
    });

    const [showDatePicker, setShowDatePicker] = useState(false);
    const [showTimePicker, setShowTimePicker] = useState(false);
    const [dateError, setDateError] = useState('');

    const handleChange = (name: string, value: string) => {
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
    };

    const handleDateChange = (event: any, selectedDate?: Date) => {
        setShowDatePicker(Platform.OS === 'ios');
        if (selectedDate) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate < today) {
                setDateError('No puedes seleccionar una fecha pasada');
                return;
            }

            setDateError('');
            const formattedDate = selectedDate.toISOString().split('T')[0];
            setFormData(prev => ({
                ...prev,
                fechaPublicacion: formattedDate
            }));
        }
    };

    const handleTimeChange = (event: any, selectedTime?: Date) => {
        setShowTimePicker(false);
        if (selectedTime) {
            const formattedTime = selectedTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            setFormData(prev => ({
                ...prev,
                horaPublicacion: formattedTime
            }));
        }
    };

    const handleSubmit = async () => {
        const selectedDate = new Date(formData.fechaPublicacion);
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (selectedDate < today) {
            Alert.alert('Error', 'No puedes seleccionar una fecha pasada');
            return;
        }

        if (!formData.titulo || !formData.descripcion) {
            Alert.alert('Error', 'Por favor completa el título y la descripción');
            return;
        }

        try {
            const response = await fetch(`${BASE_URL}/api/anunciossubir`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    ...formData,
                    persona: user?.id_Registro || 1

                })
            });

            const data = await response.json();

            if (response.ok) {
                Alert.alert('Éxito', 'Anuncio publicado correctamente');
                navigation.goBack();
            } else {
                throw new Error(data.error || 'Error al publicar anuncio');
            }
        } catch (error) {
            console.error('Error:', error);
            Alert.alert('Error', 'Ocurrió un error al publicar el anuncio');
        }
    };
    const minimumDate = new Date();

    return (
        <View style={styles.container}>

            <ScrollView contentContainerStyle={styles.scrollContent}>
                <View style={styles.header}>
                    <View style={styles.userInfo}>
                        <Image
                            source={require('./img/resi.png')}
                            style={styles.logo}
                        />
                        <View style={styles.welcomeContainer}>
                            <Text style={styles.userName}>Residente</Text>
                            <Text style={styles.welcomeText}>
                                {user ? `${user.Usuario} ` : 'Usuario'}
                            </Text>
                        </View>
                    </View>
                    <TouchableOpacity
                        style={styles.notificationIcon}
                        onPress={() => navigation.navigate('Notiresidente')}
                    >
                        <FontAwesome name="bell" size={24} color="#1d4a1d" />

                    </TouchableOpacity>
                </View>
                <Text style={styles.title}>Nuevo Anuncio</Text>

                <TextInput
                    style={styles.input}
                    placeholder="Título del anuncio*"
                    value={formData.titulo}
                    onChangeText={(text) => handleChange('titulo', text)}
                />

                <TextInput
                    style={[styles.input, styles.textArea]}
                    placeholder="Descripción*"
                    multiline
                    numberOfLines={4}
                    value={formData.descripcion}
                    onChangeText={(text) => handleChange('descripcion', text)}
                />



                {formData.img_anuncio ? (
                    <Image
                        source={{ uri: formData.img_anuncio }}
                        style={styles.imagePreview}
                        resizeMode="contain"
                    />
                ) : null}

                <TextInput
                    style={styles.input}
                    placeholder="Apartamento (ej de formato: 102A)"
                    value={formData.apart}
                    onChangeText={(text) => handleChange('apart', text)}
                    editable={true}
                />
                <View style={styles.dateContainer}>
                    <TouchableOpacity
                        style={[styles.input, dateError ? styles.inputError : null]}
                        onPress={() => setShowDatePicker(true)}
                    >
                        <Text style={styles.dateText}>Fecha de publicación: {formData.fechaPublicacion}</Text>
                        {dateError ? <Text style={styles.errorText}>{dateError}</Text> : null}
                    </TouchableOpacity>
                </View>

                {showDatePicker && (
                    <DateTimePicker
                        value={new Date(formData.fechaPublicacion)}
                        mode="date"
                        display={Platform.OS === 'ios' ? 'spinner' : 'default'}
                        onChange={handleDateChange}
                        minimumDate={minimumDate}
                    />
                )}

                <TouchableOpacity
                    style={styles.input}
                    onPress={() => setShowTimePicker(true)}
                >
                    <Text>Hora de publicación: {formData.horaPublicacion}</Text>
                </TouchableOpacity>

                {showTimePicker && (
                    <DateTimePicker
                        value={new Date()}
                        mode="time"
                        display="default"
                        onChange={handleTimeChange}
                    />
                )}

                <TouchableOpacity style={styles.button} onPress={handleSubmit}>
                    <Text style={styles.buttonText}>Publicar Anuncio</Text>
                </TouchableOpacity>

                <TouchableOpacity
                    style={styles.cancelButton}
                    onPress={() => navigation.goBack()}
                >
                    <Text style={styles.cancelButtonText}>Cancelar</Text>
                </TouchableOpacity>
            </ScrollView>

            <View style={styles.bottomNav}>
                <TouchableOpacity
                    style={styles.navItem}
                    onPress={() => navigation.navigate('ResidentePrincipal')}
                >
                    <FontAwesome name="home" size={24} color="#fff" />
                    <Text style={styles.navText}>Inicio</Text>
                </TouchableOpacity>

                <TouchableOpacity
                    style={styles.navItem}
                    onPress={() => navigation.navigate('Pagos')}
                >
                    <FontAwesome name="money" size={24} color="#fff" />
                    <Text style={styles.navText}>Pagos</Text>
                </TouchableOpacity>

                <TouchableOpacity
                    style={styles.navItem}
                    onPress={() => navigation.navigate('Perfil')}
                >
                    <FontAwesome name="user" size={24} color="#fff" />
                    <Text style={styles.navText}>Perfil</Text>
                </TouchableOpacity>
            </View>
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#f5f5f5',
    },
    scrollContent: {
        padding: 20,
        paddingBottom: 80,
    },
    title: {
        fontSize: 24,
        fontWeight: 'bold',
        marginBottom: 20,
        color: '#1d4a1d',
        textAlign: 'center',
    },

    header: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        marginBottom: 30,
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
        top: -5,
        right: -5,
        backgroundColor: 'red',
        borderRadius: 10,
        width: 18,
        height: 18,
        justifyContent: 'center',
        alignItems: 'center',
    },
    input: {
        backgroundColor: '#fff',
        padding: 15,
        borderRadius: 10,
        marginBottom: 15,
        borderWidth: 1,
        borderColor: '#ddd',
        justifyContent: 'center',
    },
    textArea: {
        height: 100,
        textAlignVertical: 'top',
    },
    button: {
        backgroundColor: '#1e871e',
        padding: 15,
        borderRadius: 10,
        alignItems: 'center',
        marginTop: 10,
    },
    buttonText: {
        color: '#fff',
        fontWeight: 'bold',
        fontSize: 16,
    },
    cancelButton: {
        backgroundColor: '#ff6b6b',
        padding: 15,
        borderRadius: 10,
        alignItems: 'center',
        marginTop: 10,
    },
    cancelButtonText: {
        color: '#fff',
        fontWeight: 'bold',
        fontSize: 16,
    },
    imagePreview: {
        width: '100%',
        height: 200,
        marginBottom: 15,
        borderRadius: 10,
    },
    bottomNav: {
        flexDirection: 'row',
        justifyContent: 'space-around',
        alignItems: 'center',
        backgroundColor: '#091f09',
        borderTopWidth: 1,
        borderTopColor: '#eee',
        paddingVertical: 10,
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
        color: '#fff',
        marginTop: 4,
        fontWeight: '900'
    },
    welcomeContainer: {
        marginTop: 10,
    },
    dateContainer: {
        marginBottom: 15,
    },
    dateText: {
        color: '#333',
    },
    errorText: {
        color: 'red',
        fontSize: 12,
        marginTop: 5,
    },
    inputError: {
        borderColor: 'red',
    },
});

export default Anunciosresi;