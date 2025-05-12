import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, ScrollView, Image, TouchableOpacity, Alert, ActivityIndicator, TextInput, SafeAreaView } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { useNavigation } from '@react-navigation/native';
import { useAuth } from '../../components/context/AuthContext';
import { FontAwesome } from '@expo/vector-icons';

type Usuario = {
    id_Registro: number;
    PrimerNombre: string;
    SegundoNombre: string;
    PrimerApellido: string;
    SegundoApellido: string;
    apartamento: string;
    Correo: string;
    telefonoUno: string;
    tipo_propietario: string;
    Usuario: string;
    Clave: string;
    imagenPerfil: string;
    numeroDocumento: string;
    Roldescripcion: string;
};

const DatosUsuarios = () => {
    const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
    const [usuarios, setUsuarios] = useState<Usuario[]>([]);
    const [loading, setLoading] = useState(true);
    const [searchText, setSearchText] = useState('');
    const { user, logout } = useAuth();

    useEffect(() => {
        const fetchUsuarios = async () => {
            try {
                const response = await fetch('http://192.168.1.105:3001/api/usuarios');
                if (!response.ok) {
                    throw new Error('Error al obtener usuarios');
                }
                const data = await response.json();
                setUsuarios(data);
            } catch (err) {
                console.error('Error fetching usuarios:', err);
                Alert.alert('Error', 'No se pudieron cargar los usuarios');
            } finally {
                setLoading(false);
            }
        };

        fetchUsuarios();
    }, []);

    const handleDelete = async (id: number) => {
        Alert.alert(
            'Confirmar eliminación',
            '¿Estás seguro de que deseas eliminar este usuario?',
            [
                {
                    text: 'Cancelar',
                    style: 'cancel',
                },
                {
                    text: 'Eliminar',
                    onPress: async () => {
                        try {
                            const response = await fetch(`http://192.168.1.105:3001/api/usuarios/${id}`, {
                                method: 'DELETE',
                            });

                            if (!response.ok) {
                                throw new Error('Error al eliminar usuario');
                            }

                            setUsuarios(usuarios.filter(user => user.id_Registro !== id));
                            Alert.alert('Éxito', 'Usuario eliminado correctamente');
                        } catch (err) {
                            console.error('Error deleting user:', err);
                            Alert.alert('Error', 'No se pudo eliminar el usuario');
                        }
                    },
                    style: 'destructive',
                },
            ],
            { cancelable: true }
        );
    };

    const filteredUsuarios = usuarios.filter(user => {
        const searchLower = searchText.toLowerCase();

        const safeToString = (value: any) => (value ? String(value).toLowerCase() : '');

        return (
            safeToString(user.PrimerNombre).includes(searchLower) ||
            safeToString(user.SegundoNombre).includes(searchLower) ||
            safeToString(user.PrimerApellido).includes(searchLower) ||
            safeToString(user.SegundoApellido).includes(searchLower) ||
            safeToString(user.Usuario).includes(searchLower) ||
            safeToString(user.apartamento).includes(searchLower) ||
            safeToString(user.Correo).includes(searchLower) ||
            safeToString(user.Roldescripcion).includes(searchLower) ||
            safeToString(user.telefonoUno).includes(searchLower) ||
            safeToString(user.tipo_propietario).includes(searchLower) ||
            safeToString(user.numeroDocumento).includes(searchLower)
        );
    });

    if (loading) {
        return (
            <View style={styles.loadingContainer}>
                <ActivityIndicator size="large" color="#0a120a" />
            </View>
        );
    }

    return (
        <SafeAreaView style={styles.safeArea}>
            <View style={styles.mainContainer}>
                <ScrollView
                    style={styles.container}
                    contentContainerStyle={styles.scrollContent}
                >
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
                            <FontAwesome name="bell" size={28} color="#19800f" />
                            <View style={styles.notificationBadge} />
                        </TouchableOpacity>
                    </View>

                    <View style={styles.header}>
                        <Text style={styles.title}>          Datos de Usuarios</Text>
                        <TouchableOpacity
                            style={styles.backButton}
                            onPress={() => navigation.goBack()}
                        >
                            <FontAwesome name="arrow-left" size={24} color="#f1fff0" />
                        </TouchableOpacity>
                    </View>

                    <View style={styles.searchContainer}>
                        <TextInput
                            style={styles.searchInput}
                            placeholder="Buscar usuario..."
                            placeholderTextColor="#999"
                            value={searchText}
                            onChangeText={setSearchText}
                        />
                        <Ionicons name="search" size={20} color="#19800f" style={styles.searchIcon} />
                    </View>

                    {filteredUsuarios.length === 0 ? (
                        <Text style={styles.noResults}>No se encontraron usuarios</Text>
                    ) : (
                        <View style={styles.tableContainer}>
                            <ScrollView horizontal={true}>
                                <View>
                                    <View style={styles.tableHeader}>
                                        <Text style={[styles.headerText, styles.smallColumn]}>N° </Text>
                                        <Text style={[styles.headerText, styles.mediumColumn]}>Rol</Text>
                                        <Text style={[styles.headerText, styles.mediumColumn]}>Numero de Documento</Text>
                                        <Text style={[styles.headerText, styles.mediumColumn]}>Nombre</Text>
                                        <Text style={[styles.headerText, styles.mediumColumn]}>Apellido</Text>
                                        <Text style={[styles.headerText, styles.mediumColumn]}>Apartamento</Text>
                                        <Text style={[styles.headerText, styles.largeColumn]}>Correo</Text>
                                        <Text style={[styles.headerText, styles.mediumColumn]}>Teléfono</Text>
                                        <Text style={[styles.headerText, styles.mediumColumn]}>Tipo de propietario</Text>
                                        <Text style={[styles.headerText, styles.mediumColumn]}>Usuario</Text>
                                        <Text style={[styles.headerText, styles.smallColumn]}>Acción</Text>
                                    </View>

                                    {filteredUsuarios.map((usuario, index) => (
                                        <View key={usuario.id_Registro} style={styles.tableRow}>
                                            <Text style={[styles.cellText, styles.smallColumn]}>{usuario.id_Registro}</Text>
                                            <Text style={[styles.cellText, styles.mediumColumn]}>{usuario.Roldescripcion}</Text>
                                            <Text style={[styles.cellText, styles.mediumColumn]}>{usuario.numeroDocumento}</Text>
                                            <Text style={[styles.cellText, styles.mediumColumn]}>{usuario.PrimerNombre}</Text>
                                            <Text style={[styles.cellText, styles.mediumColumn]}>{usuario.PrimerApellido}</Text>
                                            <Text style={[styles.cellText, styles.mediumColumn]}>{usuario.apartamento}</Text>
                                            <Text style={[styles.cellText, styles.largeColumn]} numberOfLines={1} ellipsizeMode="tail">{usuario.Correo}</Text>
                                            <Text style={[styles.cellText, styles.mediumColumn]}>{usuario.telefonoUno}</Text>
                                            <Text style={[styles.cellText, styles.mediumColumn]}>{usuario.tipo_propietario}</Text>
                                            <Text style={[styles.cellText, styles.mediumColumn]}>{usuario.Usuario}</Text>
                                            <View style={[styles.smallColumn, styles.actionCell]}>
                                                <TouchableOpacity
                                                    style={styles.deleteButton}
                                                    onPress={() => handleDelete(usuario.id_Registro)}
                                                >
                                                      <FontAwesome name="trash" size={20} color="#fff" />
                                                </TouchableOpacity>
                                            </View>
                                        </View>
                                    ))}
                                </View>
                            </ScrollView>
                        </View>
                    )}
                </ScrollView>
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
        backgroundColor: '#fff',
    },
    mainContainer: {
        flex: 1,
        marginBottom: 60, // Ajuste para el bottom nav
    },
    container: {
        flex: 1,
        backgroundColor: '#fff',
    },
    scrollContent: {
        padding: 15,
    },
    loadingContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#13660b',
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
        borderColor: '#d5dbdb',
    },
    welcomeText: {
        fontSize: 22,
        color: '#13660b',
        fontWeight: '900',
        fontFamily: 'sans-serif-light',
    },
    userName: {
        fontSize: 27,
        fontWeight: '900',
        color: '#13660b',
        fontFamily: 'sans-serif-light',
    },
    notificationIcon: {
        position: 'relative',
        backgroundColor: '#fff',
        padding: 10,
        borderRadius: 20,
        color: '#13660b',
    },
    notificationBadge: {
        position: 'absolute',
        top: 7,
        right: 5,
        width: 9,
        height: 9,
        borderRadius: 7,
        backgroundColor: '#13660b',
    },
    title: {
        fontSize: 24,
        fontWeight: 'bold',
        color: '#13660b',
        textAlign: 'center',
    },
    backButton: {
        position: 'absolute',
        left: 0,
        backgroundColor: '#13660b',
        padding: 8,
        borderRadius: 20,
    },
    searchContainer: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#dffadc',
        borderRadius: 15,
        paddingHorizontal: 15,
        marginBottom: 20,
    },
    searchInput: {
        flex: 1,
        height: 45,
        color: '#13660b',
    },
    searchIcon: {
        marginLeft: 10,
    },
    noResults: {
        textAlign: 'center',
        marginTop: 20,
        fontSize: 16,
        color: '#046314',
    },
    tableContainer: {
        marginBottom: 20,
        borderWidth: 1,
        borderColor: '#046314',
        borderRadius: 5,
        overflow: 'hidden',
    },
    tableHeader: {
        flexDirection: 'row',
        backgroundColor: '#083b10',
        paddingVertical: 10,
    },
    headerText: {
        color: '#fff',
        fontWeight: 'bold',
        textAlign: 'center',
    },
    tableRow: {
        flexDirection: 'row',
        paddingVertical: 12,
        borderBottomWidth: 1,
        borderBottomColor: '#083b10',
        alignItems: 'center',
    },
    cellText: {
        color: '#083b10',
        textAlign: 'center',
    },
    smallColumn: {
        width: 50,
        paddingHorizontal: 5,
    },
    mediumColumn: {
        width: 100,
        paddingHorizontal: 5,
    },
    largeColumn: {
        width: 150,
        paddingHorizontal: 5,
    },
    actionCell: {
        justifyContent: 'center',
        alignItems: 'center',
    },
    deleteButton: {
        backgroundColor: '#e74c3c',
        padding: 8,
        borderRadius: 5,
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
    welcomeContainer: {
        marginLeft: 5,
    },
});

export default DatosUsuarios;