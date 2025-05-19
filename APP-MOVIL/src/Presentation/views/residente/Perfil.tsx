import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ActivityIndicator, ScrollView, Alert, TextInput, Modal } from 'react-native';
import { Ionicons, MaterialIcons, FontAwesome } from '@expo/vector-icons';
import { StackNavigationProp } from '@react-navigation/stack';
import { RootStackParamList } from '../../../../App';
import { useNavigation } from '@react-navigation/native';
import { useAuth } from '../../components/context/AuthContext';
import * as ImagePicker from 'expo-image-picker';
import { manipulateAsync, SaveFormat } from 'expo-image-manipulator';

type Usuario = {
  id_Registro: number;
  PrimerNombre: string;
  SegundoNombre: string;
  PrimerApellido: string;
  SegundoApellido: string;

  Correo: string;
  telefonoUno: number;
  telefonoDos: number | null;

  Usuario: string;
  imagenPerfil: string;
  numeroDocumento: number;
  Roldescripcion: string;
  Id_tipoDocumento: string;
};

const Perfil = () => {
  const navigation = useNavigation<StackNavigationProp<RootStackParamList>>();
  const { user, logout, updateUser } = useAuth();
  const [usuario, setUsuario] = useState<Usuario>({
    id_Registro: user?.id_Registro || 0,
    PrimerNombre: user?.PrimerNombre || '',
    SegundoNombre: user?.SegundoNombre || '',
    PrimerApellido: user?.PrimerApellido || '',
    SegundoApellido: user?.SegundoApellido || '',
    Correo: user?.Correo || '',
    telefonoUno: user?.telefonoUno || 0,
    telefonoDos: user?.telefonoDos || null,
    Usuario: user?.Usuario || '',
    imagenPerfil: user?.imagenPerfil || '',
    numeroDocumento: user?.numeroDocumento || 0,
    Roldescripcion: user?.rol?.nombre || '',
    Id_tipoDocumento: user?.Id_tipoDocumento || '1'
  });
  const [loading, setLoading] = useState(true);
  const [editMode, setEditMode] = useState(false);
  const [editedUser, setEditedUser] = useState<Partial<Usuario>>({});
  const [modalVisible, setModalVisible] = useState(false);
  const [uploading, setUploading] = useState(false);

   useEffect(() => {
     const fetchUsuario = async () => {
       try {
         if (!user?.id_Registro) {
           throw new Error('ID de usuario no disponible');
         }
 
         const response = await fetch(`http://192.168.1.100:3000/api/auth/user/${user.id_Registro}`);
 
         if (!response.ok) {
           throw new Error(`Error HTTP: ${response.status}`);
         }
 
         const data = await response.json();
 
         if (data.success && data.user) {
           setUsuario({
             id_Registro: data.user.id_Registro,
             PrimerNombre: data.user.PrimerNombre || '',
             SegundoNombre: data.user.SegundoNombre || '',
             PrimerApellido: data.user.PrimerApellido || '',
             SegundoApellido: data.user.SegundoApellido || '',
             Correo: data.user.Correo || '',
             telefonoUno: data.user.telefonoUno || 0,
             telefonoDos: data.user.telefonoDos || null,
             Usuario: data.user.Usuario || '',
             imagenPerfil: data.user.imagenPerfil || '',
             numeroDocumento: data.user.numeroDocumento || 0,
             Roldescripcion: data.user.Roldescripcion || '',
             Id_tipoDocumento: data.user.Id_tipoDocumento || '1'
           });
        } else {
          throw new Error('Datos de usuario no recibidos');
        }
      } catch (err) {
        console.error('Error fetching usuario:', err);
        setUsuario({
          id_Registro: user?.id_Registro || 1,
          PrimerNombre: user?.PrimerNombre || 'residente',
          SegundoNombre: user?.SegundoNombre || '',
          PrimerApellido: user?.PrimerApellido || 'residente',
          SegundoApellido: user?.SegundoApellido || 'Sistema',
          Correo: user?.Correo || 'residente@sets.com',
          telefonoUno: user?.telefonoUno || 1234567890,
          telefonoDos: user?.telefonoDos || null,

          Usuario: user?.Usuario || 'residente',
          imagenPerfil: user?.imagenPerfil || '',
          numeroDocumento: user?.numeroDocumento || 123456789,
          Roldescripcion: user?.rol?.nombre || 'residente',
          Id_tipoDocumento: user?.Id_tipoDocumento || '1'
        });
      } finally {
        setLoading(false);
      }
    };

    fetchUsuario();
  }, [user]);

  const handleEdit = () => {
    setEditedUser(usuario || {});
    setEditMode(true);
  };

  const handleSave = async () => {
    try {
      setLoading(true);

      // Validar campos obligatorios
      if (!editedUser.PrimerNombre || !editedUser.PrimerApellido || !editedUser.Correo || !editedUser.telefonoUno) {
        Alert.alert('Error', 'Por favor complete todos los campos obligatorios');
        setLoading(false);
        return;
      }

      // Preparar los datos para enviar con valores por defecto si son undefined
      const updateData = {
        PrimerNombre: editedUser.PrimerNombre || usuario?.PrimerNombre || '',
        SegundoNombre: editedUser.SegundoNombre || usuario?.SegundoNombre || '',
        PrimerApellido: editedUser.PrimerApellido || usuario?.PrimerApellido || '',
        SegundoApellido: editedUser.SegundoApellido || usuario?.SegundoApellido || '',
        Correo: editedUser.Correo || usuario?.Correo || '',
        telefonoUno: editedUser.telefonoUno || usuario?.telefonoUno || 0,
        telefonoDos: editedUser.telefonoDos || usuario?.telefonoDos || null
      };

      const response = await fetch(`http://192.168.1.100:3000/api/auth/user/${user?.id_Registro}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(updateData)
      });

      const data = await response.json();

      if (response.ok) {
        Alert.alert('Éxito', 'Cambios guardados correctamente');

        // Actualizar el estado local con los nuevos datos
        if (usuario) {
          const updatedUser: Usuario = {
            ...usuario,
            ...updateData
          };
          setUsuario(updatedUser);

          // Actualizar el contexto de autenticación
          await updateUser(updatedUser);
        }

        setEditMode(false);
      } else {
        Alert.alert('Error', data.error || 'Error al guardar cambios');
      }
    } catch (error) {
      console.error('Error saving changes:', error);
      Alert.alert('Error', 'No se pudo conectar al servidor');
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (field: keyof Usuario, value: string) => {
    setEditedUser(prev => ({ ...prev, [field]: value }));
  };

  const handleLogout = async () => {
    Alert.alert(
      'Cerrar sesión',
      '¿Estás seguro de que deseas cerrar sesión?',
      [
        { text: 'Cancelar', style: 'cancel' },
        {
          text: 'Cerrar sesión',
          onPress: async () => {
            try {
              await logout();
              navigation.replace('HomeScreen');
            } catch (error) {
              console.error('Error al cerrar sesión:', error);
            }
          }
        }
      ]
    );
  };

   const getTipoDocumentoText = (id: string | undefined) => {
    switch (id) {
      case '1': return 'Cédula de Ciudadanía';
      case '2': return 'Cédula de ciudadanía digital';
      case '4': return 'Cédula de Extranjería';
      default: return 'Documento no especificado';
    }
  };

 const pickImage = async (source: 'camera' | 'gallery') => {
    setModalVisible(false);

    try {
      let result;
      if (source === 'camera') {
        const permissionResult = await ImagePicker.requestCameraPermissionsAsync();
        if (!permissionResult.granted) {
          Alert.alert('Permiso requerido', 'Necesitamos acceso a la cámara para tomar fotos');
          return;
        }
        result = await ImagePicker.launchCameraAsync({
          mediaTypes: ImagePicker.MediaTypeOptions.Images,
          allowsEditing: true,
          aspect: [1, 1],
          quality: 0.7,
        });
      } else {
        const permissionResult = await ImagePicker.requestMediaLibraryPermissionsAsync();
        if (!permissionResult.granted) {
          Alert.alert('Permiso requerido', 'Necesitamos acceso a tu galería para seleccionar fotos');
          return;
        }
        result = await ImagePicker.launchImageLibraryAsync({
          mediaTypes: ImagePicker.MediaTypeOptions.Images,
          allowsEditing: true,
          aspect: [1, 1],
          quality: 0.7,
        });
      }

      if (!result.canceled && result.assets && result.assets.length > 0) {
        const uri = result.assets[0].uri;
        const compressedImage = await manipulateAsync(
          uri,
          [{ resize: { width: 800 } }],
          { compress: 0.7, format: SaveFormat.JPEG }
        );
        uploadImage(compressedImage.uri);
      }
    } catch (error) {
      console.error('Error in image picker:', error);
      Alert.alert('Error', 'No se pudo seleccionar la imagen');
    }
  };

  const uploadImage = async (uri: string) => {
  try {
    setUploading(true);

    const filename = uri.split('/').pop() || 'profile.jpg';
    const match = /\.(\w+)$/.exec(filename);
    const type = match ? `image/${match[1]}` : 'image/jpeg';
    
    // Convertir la imagen a blob
    const response = await fetch(uri);
    const blob = await response.blob();

    const formData = new FormData();
    formData.append('image', {
      uri,
      name: filename,
      type
    } as any);
    formData.append('userId', user?.id_Registro?.toString() || '');

    const uploadResponse = await fetch('http://192.168.1.100:3000/api/auth/upload-profile-image', {
      method: 'POST',
      body: formData,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'multipart/form-data',
      },
    });

    const data = await uploadResponse.json();

    if (!uploadResponse.ok) {
      throw new Error(data.error || 'Error al subir la imagen');
    }

    // Actualizar el estado con la nueva URL de imagen
    const updatedUser = { ...usuario, imagenPerfil: data.imageUrl };
    setUsuario(updatedUser);
    await updateUser(updatedUser);

    Alert.alert('Éxito', 'Imagen de perfil actualizada');
  } catch (error) {
    console.error('Error uploading image:', error);
    Alert.alert('Error', 'No se pudo subir la imagen: ' + error);
  } finally {
    setUploading(false);
  }
};


  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#3498db" />
        <Text style={styles.loadingText}>Cargando perfil...</Text>
      </View>
    );
  }

  if (!usuario) {
    return (
      <View style={styles.errorContainer}>
        <Text style={styles.errorText}>Error al cargar el perfil</Text>
        <TouchableOpacity style={styles.retryButton} onPress={() => navigation.replace('HomeScreen')}>
          <Text style={styles.retryButtonText}>Volver a intentar</Text>
        </TouchableOpacity>
      </View>
    );
  }

 const getImageUrlWithCacheBuster = (url: string) => {
  if (!url) return '';
  

  if (url.startsWith('http')) {
    const separator = url.includes('?') ? '&' : '?';
    return `${url}${separator}ts=${new Date().getTime()}`;
  }
  

  const baseUrl = 'http://192.168.1.100:3000';
  const fullUrl = url.startsWith('/') ? `${baseUrl}${url}` : `${baseUrl}/${url}`;
  const separator = fullUrl.includes('?') ? '&' : '?';
  return `${fullUrl}${separator}ts=${new Date().getTime()}`;
}

  return (
    <View style={styles.container}>
      <ScrollView contentContainerStyle={styles.scrollContent}>
        <View style={styles.header}>
          <View style={styles.avatarContainer}>
            {usuario.imagenPerfil ? (
              <Image
                source={{
                  uri: getImageUrlWithCacheBuster(usuario.imagenPerfil),
                  cache: 'reload'
                }}
                style={styles.avatar}
                onError={(e) => {
                  console.log('Error loading image:', e.nativeEvent.error);
                  setUsuario(prev => ({ ...prev, imagenPerfil: '' }));
                }}
              />
            ) : (
              <View style={styles.avatarPlaceholder}>
                <MaterialIcons name="account-circle" size={100} color="#fff" />
              </View>
            )}
            <TouchableOpacity
              style={styles.editPhotoButton}
              onPress={() => setModalVisible(true)}
              disabled={uploading}
            >
              {uploading ? (
                <ActivityIndicator size="small" color="#fff" />
              ) : (
                <FontAwesome name="camera" size={20} color="#fff" />
              )}
            </TouchableOpacity>
          </View>

          <Text style={styles.name}>
            {usuario.Usuario}
          </Text>
          <Text style={styles.role}>{usuario.Roldescripcion}</Text>
        </View>

        <View style={styles.infoSection}>
          <View style={styles.sectionHeader}>
            <Text style={styles.sectionTitle}>Información Personal</Text>
            {!editMode ? (
              <TouchableOpacity onPress={handleEdit}>
                <Ionicons name="pencil-outline" size={24} color="#3498db" />
              </TouchableOpacity>
            ) : (
              <TouchableOpacity onPress={handleSave}>
                <Ionicons name="checkmark-outline" size={24} color="#2ecc71" />
              </TouchableOpacity>
            )}
          </View>

          {editMode ? (
            <>
              <View style={styles.infoItem}>
                <Text style={styles.infoLabel}>Primer Nombre:</Text>
                <TextInput
                  style={styles.input}
                  value={editedUser.PrimerNombre || ''}
                  onChangeText={(text) => handleChange('PrimerNombre', text)}
                />
              </View>

              <View style={styles.infoItem}>
                <Text style={styles.infoLabel}>Segundo Nombre:</Text>
                <TextInput
                  style={styles.input}
                  value={editedUser.SegundoNombre || ''}
                  onChangeText={(text) => handleChange('SegundoNombre', text)}
                />
              </View>

              <View style={styles.infoItem}>
                <Text style={styles.infoLabel}>Primer Apellido:</Text>
                <TextInput
                  style={styles.input}
                  value={editedUser.PrimerApellido || ''}
                  onChangeText={(text) => handleChange('PrimerApellido', text)}
                />
              </View>

              <View style={styles.infoItem}>
                <Text style={styles.infoLabel}>Segundo Apellido:</Text>
                <TextInput
                  style={styles.input}
                  value={editedUser.SegundoApellido || ''}
                  onChangeText={(text) => handleChange('SegundoApellido', text)}
                />
              </View>



              <View style={styles.infoItem}>
                <Text style={styles.infoLabel}>Número de Documento:</Text>
                <Text style={styles.infoValue}>{usuario.numeroDocumento}</Text>
              </View>

              <View style={styles.infoItem}>
                <Text style={styles.infoLabel}>Correo:</Text>
                <TextInput
                  style={styles.input}
                  value={editedUser.Correo || usuario.Correo}
                  onChangeText={(text) => handleChange('Correo', text)}
                  keyboardType="email-address"
                />
              </View>

              <View style={styles.infoItem}>
                <Text style={styles.infoLabel}>Teléfono 1:</Text>
                <TextInput
                  style={styles.input}
                  value={editedUser.telefonoUno?.toString() || usuario.telefonoUno.toString()}
                  onChangeText={(text) => handleChange('telefonoUno', text)}
                  keyboardType="phone-pad"
                />
              </View>

              <View style={styles.infoItem}>
                <Text style={styles.infoLabel}>Teléfono 2:</Text>
                <TextInput
                  style={styles.input}
                  value={editedUser.telefonoDos?.toString() || usuario.telefonoDos?.toString() || ''}
                  onChangeText={(text) => handleChange('telefonoDos', text)}
                  keyboardType="phone-pad"
                />
              </View>


            </>
          ) : (
            <>
              <View style={styles.infoItem}>
                <Text style={styles.infoLabel}>Tipo de Documento:</Text>
                <Text style={styles.infoValue}> C.C

                </Text>
              </View>

              <View style={styles.infoItem}>
                <Text style={styles.infoLabel}>Número de Documento:</Text>
                <Text style={styles.infoValue}>{usuario.numeroDocumento}</Text>
              </View>

              <View style={styles.infoItem}>
                <Text style={styles.infoLabel}>Correo:</Text>
                <Text style={styles.infoValue}>{usuario.Correo}</Text>
              </View>

              <View style={styles.infoItem}>
                <Text style={styles.infoLabel}>Teléfono 1:</Text>
                <Text style={styles.infoValue}>{usuario.telefonoUno}</Text>
              </View>

              {usuario.telefonoDos && (
                <View style={styles.infoItem}>
                  <Text style={styles.infoLabel}>Teléfono 2:</Text>
                  <Text style={styles.infoValue}>{usuario.telefonoDos}</Text>
                </View>
              )}

              <View style={styles.infoItem}>
                <Text style={styles.infoLabel}>Usuario:</Text>
                <Text style={styles.infoValue}>{usuario.Usuario}</Text>
              </View>


            </>
          )}
        </View>


        {usuario.Roldescripcion.toLowerCase().includes('admin') && (
          <View style={styles.actionsSection}>
            <Text style={styles.sectionTitle}>Acciones Administrativas</Text>

            <TouchableOpacity
              style={styles.actionButton}
              onPress={() => navigation.navigate('DatosUsuarios')}
            >
              <View style={styles.actionIcon}>
                <Ionicons name="people-outline" size={24} color="#3498db" />
              </View>
              <Text style={styles.actionText}>Administrar Usuarios</Text>
              <Ionicons name="chevron-forward-outline" size={20} color="#7f8c8d" />
            </TouchableOpacity>

            <TouchableOpacity
              style={styles.actionButton}
              onPress={() => navigation.navigate('AdminPrincipal')}
            >
              <View style={styles.actionIcon}>
                <Ionicons name="settings-outline" size={24} color="#3498db" />
              </View>
              <Text style={styles.actionText}>Configuración del Sistema</Text>
              <Ionicons name="chevron-forward-outline" size={20} color="#7f8c8d" />
            </TouchableOpacity>
          </View>
        )}


        <TouchableOpacity
          style={styles.logoutButton}
          onPress={handleLogout}
        >
          <FontAwesome name="sign-out" size={20} color="#e74c3c" />
          <Text style={styles.logoutText}>Cerrar Sesión</Text>
        </TouchableOpacity>
      </ScrollView>


      <Modal
        animationType="slide"
        transparent={true}
        visible={modalVisible}
        onRequestClose={() => setModalVisible(false)}
      >
        <View style={styles.modalContainer}>
          <View style={styles.modalContent}>
            <Text style={styles.modalTitle}>Cambiar Foto de Perfil</Text>

            <TouchableOpacity
              style={styles.modalOption}
              onPress={() => pickImage('camera')}
            >
              <Ionicons name="camera-outline" size={24} color="#3498db" />
              <Text style={styles.modalOptionText}>Tomar Foto</Text>
            </TouchableOpacity>

            <TouchableOpacity
              style={styles.modalOption}
              onPress={() => pickImage('gallery')}
            >
              <Ionicons name="image-outline" size={24} color="#3498db" />
              <Text style={styles.modalOptionText}>Elegir de Galería</Text>
            </TouchableOpacity>

            <TouchableOpacity
              style={styles.modalCancel}
              onPress={() => setModalVisible(false)}
            >
              <Text style={styles.modalCancelText}>Cancelar</Text>
            </TouchableOpacity>
          </View>
        </View>
      </Modal>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f8f9fa',
  },
  scrollContent: {
    padding: 20,
    paddingBottom: 40,
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f8f9fa',
  },
  loadingText: {
    marginTop: 10,
    color: '#13400f',
    fontSize: 16,
  },
  errorContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f8f9fa',
    padding: 20,
  },
  errorText: {
    fontSize: 18,
    color: '#e74c3c',
    marginBottom: 20,
  },
  retryButton: {
    backgroundColor: '#3498db',
    padding: 15,
    borderRadius: 8,
  },
  retryButtonText: {
    color: '#fff',
    fontWeight: 'bold',
  },
  header: {
    alignItems: 'center',
    marginBottom: 30,
    backgroundColor: '#13400f',
    padding: 20,
    borderRadius: 10,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.2,
    shadowRadius: 4,
    elevation: 3,
  },
  avatarContainer: {
    position: 'relative',
    marginBottom: 15,
  },
  avatar: {
    width: 120,
    height: 120,
    borderRadius: 60,
    borderWidth: 3,
    borderColor: '#fff',
  },
  avatarPlaceholder: {
    width: 120,
    height: 120,
    borderRadius: 60,
    backgroundColor: '#13400f',
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 3,
    borderColor: '#fff',
  },
  editPhotoButton: {
    position: 'absolute',
    bottom: 0,
    right: 0,
    backgroundColor: '#13400f',
    width: 40,
    height: 40,
    borderRadius: 20,
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 2,
    borderColor: '#fff',
  },
  name: {
    fontSize: 22,
    fontWeight: 'bold',
    color: '#fff',
    textAlign: 'center',
    marginTop: 10,
  },
  role: {
    fontSize: 16,
    color: '#ecf0f1',
    textAlign: 'center',
    marginTop: 5,
    fontStyle: 'italic',
  },
  infoSection: {
    backgroundColor: '#fff',
    borderRadius: 10,
    padding: 20,
    marginBottom: 20,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 2,
  },
  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 15,
    borderBottomWidth: 1,
    borderBottomColor: '#13400f',
    paddingBottom: 10,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#2c3e50',
  },
  infoItem: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 12,
    alignItems: 'center',
  },
  infoLabel: {
    fontSize: 16,
    color: '#13400f',
    fontWeight: '500',
    flex: 1,
  },
  infoValue: {
    fontSize: 16,
    color: '#2c3e50',
    fontWeight: '500',
    flex: 1,
    textAlign: 'right',
  },
  input: {
    flex: 1,
    borderBottomWidth: 1,
    borderBottomColor: '#13400f',
    paddingVertical: 5,
    fontSize: 16,
    color: '#2c3e50',
    marginLeft: 10,
  },
  actionsSection: {
    backgroundColor: '#fff',
    borderRadius: 10,
    padding: 20,
    marginBottom: 20,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 2,
  },
  actionButton: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 15,
    borderBottomWidth: 1,
    borderBottomColor: '#ecf0f1',
  },
  actionIcon: {
    width: 40,
    alignItems: 'center',
    marginRight: 15,
  },
  actionText: {
    flex: 1,
    fontSize: 16,
    color: '#2c3e50',
  },
  logoutButton: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    padding: 15,
    backgroundColor: '#fff',
    borderRadius: 10,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 2,
  },
  logoutText: {
    fontSize: 16,
    color: '#e74c3c',
    fontWeight: 'bold',
    marginLeft: 10,
  },
  modalContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: 'rgba(0,0,0,0.5)',
  },
  modalContent: {
    backgroundColor: '#fff',
    borderRadius: 10,
    padding: 20,
    width: '80%',
  },
  modalTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#2c3e50',
    marginBottom: 20,
    textAlign: 'center',
  },
  modalOption: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 15,
    borderBottomWidth: 1,
    borderBottomColor: '#ecf0f1',
  },
  modalOptionText: {
    fontSize: 16,
    color: '#2c3e50',
    marginLeft: 15,
  },
  modalCancel: {
    marginTop: 15,
    padding: 10,
    alignItems: 'center',
  },
  modalCancelText: {
    fontSize: 16,
    color: '#e74c3c',
    fontWeight: 'bold',
  },
});

export default Perfil;