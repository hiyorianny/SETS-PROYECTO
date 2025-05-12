import React, { useEffect, useState } from "react";
import { View, StyleSheet, Image, Text, Animated,  } from 'react-native';
import { StackNavigationProp } from "@react-navigation/stack";
import { RootStackParamList } from "../../../../App";
import { useNavigation } from "@react-navigation/native";


export const residentebienvenido: React.FC = () => {
    const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
    const [fadeAnim] = useState(new Animated.Value(0));


    useEffect(() => {
        Animated.timing(
            fadeAnim, {
            toValue: 1,
            duration: 1000,
            useNativeDriver: true,
        }
        ).start();


        const timer = setTimeout(() => {
            navigation.replace('ResidentePrincipal');
        }, 2000);
        return () => clearTimeout(timer);
    }, [navigation, fadeAnim]);


    return (
        <Animated.View style={[styles.container, { opacity: fadeAnim }]}>
            <Image
                source={require('./img/resi.png')}
                style={styles.logo}
            />

            <Text style={styles.welcomeTitle}>¡Bienvenido residente!</Text>

            <Text style={styles.welcomeSubtitle}>Tu registro ha sido exitoso</Text>

            <View style={styles.messageBox}>
                <Text style={styles.messageText}>
                    Ahora puedes comenzar a usar todas las funciones  disponibles.
                </Text>
            </View>
        </Animated.View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#ffff',

    },
    logo: {
        width: 150,
        height: 150,
        marginBottom: 25,
    },
    welcomeTitle: {
        fontSize: 28,
        fontWeight: 'bold',
        color: '#0f521b',
        marginBottom: 10,
    },
    welcomeSubtitle: {
        fontSize: 19,
        color: '#0f521b',
        marginBottom: 30,
    },
    messageBox: {
        backgroundColor: '#e1f7ec',
        padding: 23,
        borderRadius: 13,
        width: '80%',
    },
    messageText: {
        textAlign: 'center',
        color: '#0f521b',
        fontSize: 18,
    },
});