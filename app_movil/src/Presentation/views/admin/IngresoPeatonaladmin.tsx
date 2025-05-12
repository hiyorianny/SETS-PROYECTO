import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, FlatList, SafeAreaView, ActivityIndicator, Alert } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { useNavigation } from '@react-navigation/native';
import { useAuth } from '../../components/context/AuthContext';
import { FontAwesome } from '@expo/vector-icons';


type IngresoPeatonal = {
    idIngreso_Peatonal: number;
    personasIngreso: string;
    horaFecha: string;
    documento: string;
    tipo_ingreso: string;
    placa: string | null;
};

const IngresoPeatonalAdmin = () => {
    const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
    const [ingresos, setIngresos] = useState<IngresoPeatonal[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const { user } = useAuth();

    const fetchIngresos = async () => {
        try {
            const response = await fetch('http://192.168.1.105:3001/api/ingresos');
            if (!response.ok) {
                throw new Error('Error al obtener ingresos peatonales');
            }
            const data = await response.json();
            setIngresos(data);
        } catch (err) {
            console.error('Error fetching ingresos:', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchIngresos();
    }, []);

    const handleDelete = async (id: number) => {
        Alert.alert(
            'Confirmar eliminación',
            '¿Estás seguro de que deseas eliminar este ingreso?',
            [
                {
                    text: 'Cancelar',
                    style: 'cancel',
                },
                {
                    text: 'Eliminar',
                    onPress: async () => {
                        try {
                            const response = await fetch(`http://192.168.1.105:3001/api/ingresos/${id}`, {
                                method: 'DELETE',
                            });

                            if (!response.ok) {
                                throw new Error('Error al eliminar el ingreso');
                            }

                            fetchIngresos();
                        } catch (err) {
                            console.error('Error al eliminar ingreso:', err);
                            Alert.alert('Error', 'No se pudo eliminar el ingreso');
                        }
                    },
                    style: 'destructive',
                },
            ],
            { cancelable: true }
        );
    };

    const renderItem = ({ item }: { item: IngresoPeatonal }) => (
        <View style={styles.itemContainer}>
            <View style={styles.itemHeader}>
                <Text style={styles.itemId}>ID: {item.idIngreso_Peatonal}</Text>
                <Text style={styles.itemType}>{item.tipo_ingreso.toUpperCase()}</Text>
            </View>
            <Text style={styles.itemText}>Persona: {item.personasIngreso}</Text>
            <Text style={styles.itemText}>Fecha/Hora: {new Date(item.horaFecha).toLocaleString()}</Text>
            <Text style={styles.itemText}>Documento: {item.documento}</Text>
            {item.placa && <Text style={styles.itemText}>Placa: {item.placa}</Text>}

            <TouchableOpacity
                style={styles.deleteButton}
                onPress={() => handleDelete(item.idIngreso_Peatonal)}
            >
                <Ionicons name="trash-outline" size={20} color="white" />
                <Text style={styles.deleteButtonText}>Eliminar</Text>
            </TouchableOpacity>
        </View>
    );

    return (
        <SafeAreaView style={styles.safeArea}>
            <View style={styles.mainContainer}>
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
                    <TouchableOpacity onPress={() => navigation.goBack()} style={styles.backButton}>
                        <FontAwesome name="arrow-left" size={24} color="#19800f" />
                    </TouchableOpacity>
                    <Text style={styles.title}>Ingresos Peatonales</Text>
                </View>

                {loading ? (
                    <ActivityIndicator size="large" color="#083b10" style={styles.loader} />
                ) : error ? (
                    <View style={styles.errorContainer}>
                        <Text style={styles.errorText}>{error}</Text>
                        <TouchableOpacity onPress={fetchIngresos} style={styles.retryButton}>
                            <Text style={styles.retryButtonText}>Reintentar</Text>
                        </TouchableOpacity>
                    </View>
                ) : (
                    <FlatList
                        data={ingresos}
                        renderItem={renderItem}
                        keyExtractor={(item) => item.idIngreso_Peatonal.toString()}
                        contentContainerStyle={styles.listContainer}
                        style={styles.listStyle}
                        ListEmptyComponent={
                            <View style={styles.emptyContainer}>
                                <Text style={styles.emptyText}>No hay ingresos registrados</Text>
                            </View>
                        }
                    />
                )}
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
        marginBottom: 60, 
    },
    header: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        marginBottom: 40,
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
        color: '#083004',
        fontWeight: '900',
        fontFamily: 'sans-serif-light',
    },
    userName: {
        fontSize: 27,
        fontWeight: '900',
        color: '#083004',
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
        backgroundColor: '#e74c3c',
    },
    backButton: {
        marginRight: 15,
    },
    title: {
        fontSize: 20,
        fontWeight: 'bold',
        color: '#083b10',
    },
    loader: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
    },
    errorContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        padding: 20,
    },
    errorText: {
        color: '#e74c3c',
        fontSize: 16,
        marginBottom: 20,
        textAlign: 'center',
    },
    retryButton: {
        backgroundColor: '#083004',
        padding: 10,
        borderRadius: 5,
    },
    retryButtonText: {
        color: 'white',
        fontWeight: 'bold',
    },
    listContainer: {
        padding: 15,
    },
    listStyle: {
        flex: 1, 
    },
    itemContainer: {
        backgroundColor: '#072b0d',
        borderRadius: 8,
        padding: 15,
        marginBottom: 15,
        borderWidth: 1,
        borderColor: '#e0e0e0',
    },
    itemHeader: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        marginBottom: 10,
    },
    itemId: {
        fontSize: 14,
        color: '#fff',
        fontWeight: 'bold',
    },
    itemType: {
        fontSize: 14,
        color: '#fff',
        fontWeight: 'bold',
        textTransform: 'capitalize',
    },
    itemText: {
        fontSize: 16,
        color: '#fff',
        marginBottom: 5,
    },
    deleteButton: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        backgroundColor: '#e74c3c',
        padding: 10,
        borderRadius: 5,
        marginTop: 10,
    },
    deleteButtonText: {
        color: 'white',
        fontWeight: 'bold',
        marginLeft: 5,
    },
    emptyContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        padding: 20,
    },
    emptyText: {
        fontSize: 16,
        color: '#7f8c8d',
        textAlign: 'center',
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

export default IngresoPeatonalAdmin;