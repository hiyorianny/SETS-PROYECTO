import React, { useState } from 'react';
import { View, Text, StyleSheet, TextInput, TouchableOpacity, Alert, ImageBackground, ScrollView } from 'react-native';
import { Picker } from '@react-native-picker/picker';
import { useNavigation } from '@react-navigation/native';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { FontAwesome } from '@expo/vector-icons';
import DateTimePicker, { DateTimePickerEvent } from '@react-native-community/datetimepicker';

const NuevoPago = () => {
    const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
    const [showDatePicker, setShowDatePicker] = useState(false);
    const [dateError, setDateError] = useState<string | null>(null);
    
    const [form, setForm] = useState({
        pagoPor: 'Mantenimiento',
        cantidad: '',
        mediopago: 'Transferencia',
        apart: '',
        fechaPago: new Date().toISOString().split('T')[0], 
        estado: 'Pendiente',
        referenciaPago: ''
    });

    const handleDateChange = (event: DateTimePickerEvent, selectedDate?: Date) => {
        setShowDatePicker(false);
        
        if (event.type === 'set' && selectedDate) {
            const today = new Date();
            today.setHours(0, 0, 0, 0); 
            

            if (selectedDate < today) {
                setDateError('No se puede seleccionar una fecha anterior a hoy');
                return;
            }
            

            const dateString = selectedDate.toISOString().split('T')[0];
            if (!/^\d{4}-\d{2}-\d{2}$/.test(dateString)) {
                setDateError('Formato de fecha inválido');
                return;
            }
            
            setDateError(null);
            setForm({
                ...form,
                fechaPago: dateString
            });
        }
    };

    const validateForm = () => {

        if (!form.apart || !form.cantidad || !form.fechaPago) {
            Alert.alert('Error', 'Por favor complete todos los campos obligatorios');
            return false;
        }
        

        if (isNaN(parseFloat(form.cantidad)) || parseFloat(form.cantidad) <= 0) {
            Alert.alert('Error', 'Ingrese un monto válido mayor a cero');
            return false;
        }
        

        const selectedDate = new Date(form.fechaPago);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            Alert.alert('Error', 'No se puede seleccionar una fecha anterior a hoy');
            return false;
        }
        
        return true;
    };

    const handleSubmit = async () => {
        if (!validateForm()) return;

        try {
            const response = await fetch('http://192.168.1.105:3001/api/pagos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    ...form,
                    cantidad: parseFloat(form.cantidad)
                }),
            });

            if (response.ok) {
                Alert.alert('Éxito', 'Pago registrado correctamente');
                navigation.goBack();
            } else {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Error al registrar el pago');
            }
        } catch (error) {
            console.error('Error:', error);
            Alert.alert('Error', error instanceof Error ? error.message : 'No se pudo registrar el pago');
        }
    };

    return (
        <ImageBackground 
            source={require('./img/paseo.jpg')} 
            style={styles.background}
            imageStyle={{ opacity: 0.9 }}
        >
            <ScrollView style={styles.container}>
            <View style={styles.header}>
                  
                    <View style={{ width: 24 }} />
                </View>
                <View style={styles.header}>
                    <TouchableOpacity onPress={() => navigation.goBack()}>
                        <FontAwesome name="arrow-left" size={24} color="#0b4705" />
                    </TouchableOpacity>
                    <Text style={styles.title}>Nuevo Pago</Text>
                    <View style={{ width: 24 }} />
                </View>

                <View style={styles.formGroup}>
                    <Text style={styles.label}>Concepto de pago:</Text>
                    <Picker
                        selectedValue={form.pagoPor}
                        onValueChange={(value) => setForm({ ...form, pagoPor: value })}
                        style={styles.input}
                    >
                        <Picker.Item label="Mantenimiento" value="Mantenimiento" />
                        <Picker.Item label="Servicios" value="Servicios" />
                        <Picker.Item label="Multa" value="Multa" />
                        <Picker.Item label="Reserva Zona comun" value="Reserva Zona comun" />
                        <Picker.Item label="Parqueaderos" value="Parqueaderos" />
                        <Picker.Item label="Administracion" value="Administracion" />
                    </Picker>
                </View>

                <View style={styles.formGroup}>
                    <Text style={styles.label}>Apartamento del pago:</Text>
                    <TextInput
                        style={styles.input}
                        value={form.apart}
                        onChangeText={(text) => setForm({ ...form, apart: text })}
                        placeholder="Ej: A101"
                    />
                </View>

                <View style={styles.formGroup}>
                    <Text style={styles.label}>Monto:</Text>
                    <TextInput
                        style={styles.input}
                        value={form.cantidad}
                        onChangeText={(text) => setForm({ ...form, cantidad: text })}
                        placeholder="0.00"
                        keyboardType="numeric"
                    />
                </View>

                <View style={styles.formGroup}>
                    <Text style={styles.label}>Fecha de Pago:</Text>
                    <TouchableOpacity 
                        style={[styles.input, dateError ? styles.inputError : null]}
                        onPress={() => setShowDatePicker(true)}
                    >
                        <Text>{form.fechaPago}</Text>
                    </TouchableOpacity>
                    {dateError && <Text style={styles.errorText}>{dateError}</Text>}
                    
                    {showDatePicker && (
                        <DateTimePicker
                            value={new Date(form.fechaPago)}
                            mode="date"
                            display="default"
                            minimumDate={new Date()} 
                            onChange={handleDateChange}
                        />
                    )}
                </View>

                <View style={styles.formGroup}>
                    <Text style={styles.label}>Método de pago:</Text>
                    <Picker
                        selectedValue={form.mediopago}
                        onValueChange={(value) => setForm({ ...form, mediopago: value })}
                        style={styles.input}
                    >
                        <Picker.Item label="Transferencia" value="Transferencia" />
                        <Picker.Item label="Efectivo" value="Efectivo" />
                        <Picker.Item label="Tarjeta" value="Tarjeta" />
                        <Picker.Item label="Cheque" value="Cheque" />
                        <Picker.Item label="Otro" value="Otro" />
                    </Picker>
                </View>

                <View style={styles.formGroup}>
                    <Text style={styles.label}>Referencia (opcional):</Text>
                    <TextInput
                        style={styles.input}
                        value={form.referenciaPago}
                        onChangeText={(text) => setForm({ ...form, referenciaPago: text })}
                        placeholder="Número de referencia"
                    />
                </View>

                <TouchableOpacity style={styles.submitButton} onPress={handleSubmit}>
                    <Text style={styles.submitButtonText}>Registrar Pago</Text>
                </TouchableOpacity>
            </ScrollView>
        </ImageBackground>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        padding: 16,
        backgroundColor: 'rgba(243, 255, 240, 0.85)',
    },
    background: {
        flex: 1,
        backgroundColor: '#f5f1e6',
    },
    header: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        marginBottom: 20,
    },
    title: {
        fontSize: 22,
        fontWeight: 'bold',
        color: '#072b0d',
    },
    formGroup: {
        marginBottom: 16,
    },
    label: {
        marginBottom: 8,
        fontSize: 16,
        color: '#073812',
        fontWeight: 'bold',
    },
    input: {
        backgroundColor: '#fff',
        borderWidth: 1,
        borderColor: '#ddd',
        borderRadius: 4,
        padding: 12,
        fontSize: 16,
    },
    inputError: {
        borderColor: '#ff0000',
    },
    errorText: {
        color: '#ff0000',
        fontSize: 14,
        marginTop: 5,
    },
    submitButton: {
        backgroundColor: '#1d4a1d',
        padding: 15,
        borderRadius: 4,
        alignItems: 'center',
        marginTop: 20,
        marginBottom: 30,
    },
    submitButtonText: {
        color: '#fff',
        fontSize: 18,
        fontWeight: 'bold',
    },
});

export default NuevoPago;