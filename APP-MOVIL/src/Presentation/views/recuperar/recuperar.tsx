import React from 'react';
import { View, Text, StyleSheet, Image, TextInput,ToastAndroid, TouchableOpacity } from 'react-native';
import { RoundedButton } from '../../components/RoundedButton';
import { useNavigation } from '@react-navigation/native';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';

import styles from './Styles';

export const ForgotPasswordScreen = () => {
    const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();

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
                <Text style={styles.formTitle}>RECUPERAR CONTRASEÑA</Text>

        
                <View style={styles.formInput}>
                    <Image
                        source={require('../../../../assets/email.png')}
                        style={styles.formIcon}
                    />
                    <TextInput
                        style={styles.formTextInput}
                        placeholder='Correo Electrónico'
                        keyboardType='email-address'
                        autoCapitalize='none'
                    />
                </View>

                <View style={styles.buttonContainer}>
                    <RoundedButton
                        text='ENVIAR'
                        onPress={() => {
                            console.log('Instrucciones enviadas');
                            ToastAndroid.show('Correo enviado con éxito', ToastAndroid.SHORT);
                        }}
                    />
                </View>

          
                <View style={styles.formFooter}>
                    <Text style={styles.footerText}>¿Recordaste tu contraseña?</Text>
                    <TouchableOpacity onPress={() => navigation.navigate('HomeScreen')}>
                        <Text style={styles.footerLink}>Iniciar Sesión</Text>
                    </TouchableOpacity>
                </View>
            </View>
        </View>
    );
};