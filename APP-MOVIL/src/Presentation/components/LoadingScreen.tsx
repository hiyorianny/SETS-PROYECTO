import React from 'react';
import { View, Text, StyleSheet, ActivityIndicator, Image } from 'react-native';

export const LoadingScreen = () => {
    return (
        <View style={styles.container}>
           
            <Image
                source={require('../../../assets/img/c.png')} 
                style={styles.logo}
            />
           
            <ActivityIndicator size="large" color="#097329" />
        
       
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#fff', 
    },
    logo: {
        width: 250, 
        height: 250, 
        resizeMode: 'contain', 
        marginBottom: 20, 
    },
    loadingText: {
        marginTop: 10,
        fontSize: 18,
        color: '#000',
    },
});