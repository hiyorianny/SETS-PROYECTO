import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, TextInput, Image, FlatList, SafeAreaView, ActivityIndicator, Alert } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { useNavigation } from '@react-navigation/native';
import { useAuth } from '../../components/context/AuthContext';
import { FontAwesome } from '@expo/vector-icons';

type Contacto = {
    idcontactarnos: number;
    nombre: string;
    correo: string;
    telefono: string;
    comentario: string;
    fecha: string;
};

const Contactanosadmin = () => {
    const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
    const [contactos, setContactos] = useState<Contacto[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const { user, logout } = useAuth();
    const [searchText, setSearchText] = useState('');

    useEffect(() => {
        fetchContactos();
    }, []);

    const fetchContactos = async () => {
        try {
            setLoading(true);
            const response = await fetch('http://192.168.1.105:3001/api/contactarnos');
            if (!response.ok) {
                throw new Error('Error al obtener contactos');
            }
            const data = await response.json();
            setContactos(data);
        } catch (err) {
            console.error('Error fetching contactos:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleDelete = async (id: number) => {
        try {
            const response = await fetch(`http://192.168.1.105:3001/api/contactarnos/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Error al eliminar el contacto');
            }

            fetchContactos();
            Alert.alert('Éxito', 'Contacto eliminado correctamente');
        } catch (error) {
            console.error('Error al eliminar:', error);
            Alert.alert('Error', 'No se pudo eliminar el contacto');
        }
    };

    const confirmDelete = (id: number) => {
        Alert.alert(
            'Confirmar eliminación',
            '¿Estás seguro de que deseas eliminar este contacto?',
            [
                { text: 'Cancelar', style: 'cancel' },
                { text: 'Eliminar', onPress: () => handleDelete(id) }
            ]
        );
    };

    const filteredContactos = contactos.filter(contacto =>
        contacto.nombre.toLowerCase().includes(searchText.toLowerCase()) ||
        contacto.correo.toLowerCase().includes(searchText.toLowerCase()) ||
        contacto.telefono.toLowerCase().includes(searchText.toLowerCase()) ||
        contacto.comentario.toLowerCase().includes(searchText.toLowerCase())
    );

    const renderItem = ({ item }: { item: Contacto }) => (
        <View style={styles.contactoItem}>
            <View style={styles.contactoHeader}>
                <Text style={styles.contactoNombre}>{item.nombre}</Text>
                <Text style={styles.contactoFecha}>{new Date(item.fecha).toLocaleDateString()}</Text>
            </View>
            <Text style={styles.contactoInfo}>Correo: {item.correo}</Text>
            <Text style={styles.contactoInfo}>Teléfono: {item.telefono}</Text>
            <Text style={styles.contactoComentario}>{item.comentario}</Text>

            <TouchableOpacity
                style={styles.deleteButton}
                onPress={() => confirmDelete(item.idcontactarnos)}
            >
                  <FontAwesome name="trash" size={20} color="white" />
                <Text style={styles.deleteButtonText}>Eliminar</Text>
            </TouchableOpacity>
        </View>
    );

    return (
        <SafeAreaView style={styles.safeArea}>
            <View style={styles.mainContainer}>
                <View style={styles.container}>
                    <View style={styles.header}></View>
                    <View style={styles.header}>
                        <View style={styles.userInfo}>
                            <Image
                                source={require('./img/ajustes.png')}
                                style={styles.logo}
                            />
                            <View style={styles.welcomeContainer}>
                                <Text style={styles.userName}>Admin</Text>
                                <Text style={styles.welcomeText}>
                                    {user ? `${user.Usuario} ` : 'Usuario'}
                                </Text>
                            </View>
                        </View>
                        <TouchableOpacity
                            style={styles.notificationIcon}
                            onPress={() => navigation.navigate('Notificacionesadmin')}
                        >
                            <FontAwesome name="bell" size={28} color="#0d4008" />
                            <View style={styles.notificationBadge} />
                        </TouchableOpacity>
                    </View>

                    <View style={styles.header}>
                        <TouchableOpacity onPress={() => navigation.goBack()}>
                            <FontAwesome name="arrow-left" size={24} color="#0b4705" />
                        </TouchableOpacity>
                        <Text style={styles.title}>Responder Dudas e Inquietudes</Text>
                        <View style={{ width: 24 }} />
                    </View>

                    <View style={styles.searchContainer}>
                        <Ionicons name="search-outline" size={20} color="#17660f" style={styles.searchIcon} />
                        <TextInput
                            style={styles.searchInput}
                            placeholder="Buscar contacto..."
                            value={searchText}
                            onChangeText={setSearchText}
                            placeholderTextColor="#888"
                        />
                    </View>

                    {loading ? (
                        <ActivityIndicator size="large" color="#17660f" style={styles.loader} />
                    ) : error ? (
                        <Text style={styles.errorText}>{error}</Text>
                    ) : (
                        <View style={styles.listWrapper}>
                            <FlatList
                                data={filteredContactos}
                                renderItem={renderItem}
                                keyExtractor={item => item.idcontactarnos.toString()}
                                contentContainerStyle={styles.listContent}
                                ListEmptyComponent={
                                    <Text style={styles.emptyText}>No se encontraron solicitudes de ayudas</Text>
                                }
                            />
                        </View>
                    )}
                </View>
            </View>

            <View style={styles.bottomNav}>
                <TouchableOpacity
                    style={styles.navItem}
                    onPress={() => navigation.navigate('AdminPrincipal')}
                >
                    <FontAwesome name="home" size={24} color="#ecf0f1" />
                    <Text style={styles.navText}>Inicio</Text>
                </TouchableOpacity>

                <TouchableOpacity
                    style={styles.navItem}
                    onPress={() => navigation.navigate('PerfilAdmin')}
                >
                    <FontAwesome name="user" size={24} color="#ecf0f1" />
                    <Text style={styles.navText}>Perfil</Text>
                </TouchableOpacity>

            
            </View>
        </SafeAreaView>
    );
};

const styles = StyleSheet.create({
    safeArea: {
        flex: 1,
        backgroundColor: '#17660f',
    },
    mainContainer: {
        flex: 1,
        marginBottom: 60, // Ajuste para el bottom nav
    },
    container: {
        flex: 1,
        padding: 16,
        backgroundColor: '#fff',
    },
    listWrapper: {
        flex: 1, // Esto permite que el FlatList ocupe el espacio restante
    },
    header: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        marginBottom: 50,
        paddingHorizontal: 9,
    },
    userInfo: {
        flexDirection: 'row',
        alignItems: 'center',
    },
    logo: {
        width: 86,
        height: 80,
        borderRadius: 3,
        borderColor: '#17660f',
    },
    welcomeText: {
        fontSize: 22,
        color: '#17660f',
        fontWeight: '900',
        fontFamily: 'sans-serif-light',
    },
    userName: {
        fontSize: 27,
        fontWeight: '900',
        color: '#17660f',
        fontFamily: 'sans-serif-light',
    },
    notificationIcon: {
        position: 'relative',
        backgroundColor: '#fff',
        padding: 10,
        borderRadius: 20,
        color: '#fff',
    },
    notificationBadge: {
        position: 'absolute',
        top: 7,
        right: 5,
        width: 9,
        height: 9,
        borderRadius: 7,
        backgroundColor: '#17660f',
    },
    title: {
        fontSize: 22,
        fontWeight: 'bold',
        color: '#17660f',
    },
    searchContainer: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#d3ffcf',
        borderRadius: 18,
        paddingHorizontal: 32,
        marginBottom: 26,
    },
    searchIcon: {
        marginRight: 8,
    },
    searchInput: {
        flex: 1,
        height: 40,
        color: '#d3ffcf',
    },
    listContent: {
        paddingBottom: 20,
    },
    contactoItem: {
        backgroundColor: '#051f03',
        borderRadius: 8,
        padding: 16,
        marginBottom: 12,
        borderWidth: 1,
        borderColor: '#e0e0e0',
    },
    contactoHeader: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        marginBottom: 8,
    },
    contactoNombre: {
        fontSize: 16,
        fontWeight: 'bold',
        color: '#ffff',
    },
    contactoFecha: {
        fontSize: 12,
        color: '#fff',
    },
    contactoInfo: {
        fontSize: 14,
        color: '#fff',
        marginBottom: 4,
    },
    contactoComentario: {
        fontSize: 14,
        color: '#fff',
        marginTop: 8,
        fontStyle: 'italic',
    },
    deleteButton: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        backgroundColor: '#e74c3c',
        padding: 8,
        borderRadius: 5,
        marginTop: 10,
    },
    deleteButtonText: {
        color: 'white',
        marginLeft: 5,
        fontWeight: 'bold',
    },
    loader: {
        marginTop: 50,
    },
    errorText: {
        color: '#17660f',
        textAlign: 'center',
        marginTop: 20,
    },
    emptyText: {
        textAlign: 'center',
        color: '#888',
        marginTop: 20,
    },
    footer: {
        alignItems: 'center',
        marginTop: 20,
        padding: 10,
    },
    footerText: {
        fontSize: 12,
        color: '#0a120a',
    },
    bottomNav: {
        flexDirection: 'row',
        justifyContent: 'space-around',
        alignItems: 'center',
        backgroundColor: '#0a120a',
        paddingVertical: 12,
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
        fontSize: 14,
        color: '#ecf0f1',
        marginTop: 4,
        fontWeight: '900'
    },
    scrollContent: {
        padding: 15,
        paddingTop: 20,
    },
    welcomeContainer: {
        marginLeft: 5,
    },
});

export default Contactanosadmin;