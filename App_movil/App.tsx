import * as React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { HomeScreen } from './src/Presentation/views/home/home';
import { RegisterScreen } from './src/Presentation/views/register/Register';
import { LoadingScreen } from './src/Presentation/components/LoadingScreen';
import { ForgotPasswordScreen } from './src/Presentation/views/recuperar/recuperar';
import { GuardaLoadingScreen } from './src/Presentation/views/guarda/GuardaLoadingScreen';
import { ResidenteLoadingScreen } from './src/Presentation/views/residente/ResidenteLoadingScreen';
import { AdminLoadingScreen } from './src/Presentation/views/admin/AdminLoadingScreen';
import { registeradminloading } from './src/Presentation/views/admin/registeradminloading';
import { registerloadinresidente } from './src/Presentation/views/residente/registerloadinresidente';
import { guardaregistroloading } from './src/Presentation/views/guarda/guardaregistroloading';
import { residentebienvenido } from './src/Presentation/views/residente/bienvenidoresidente';
import { Guardabienvenido } from './src/Presentation/views/guarda/bienvenidoguarda';
import { adminbienvenido } from './src/Presentation/views/admin/bienvenidoadmin';
import ResidentePrincipal from './src/Presentation/views/residente/residenteprincipal';
import GuardaPrincipal from "./src/Presentation/views/guarda/guardaprincipal";
import AdminPrincipal from './src/Presentation/views/admin/adminprincipal';
import { AuthProvider } from './src/Presentation/components/context/AuthContext';
import Parqueaderoresidente from './src/Presentation/views/residente/parqueadero';
import ZonasComunes from './src/Presentation/views/residente/ZonasComunes';
import Pagos from './src/Presentation/views/residente/Pagos';
import Perfil from './src/Presentation/views/residente/Perfil';
import Zonasguarda from './src/Presentation/views/guarda/zonasguarda';
import Citas from './src/Presentation/views/residente/citas';
import Torres from './src/Presentation/views/guarda/torreguarda';
import Notificacionesguarda from './src/Presentation/views/guarda/Notificacionesguarda';
import IngresoPeatonal from './src/Presentation/views/guarda/IngresoPeatonal';
import Parqueaderoguarda from "./src/Presentation/views/guarda/parqueaderoguarda";
import guardaperfil from './src/Presentation/views/guarda/guardaperfil';
import Contactanosadmin from "./src/Presentation/views/admin/contactarnos";
import DatosUsuarios from "./src/Presentation/views/admin/datosusuario";
import Torresadmin from "./src/Presentation/views/admin/torresadmin";
import IngresoPeatonalAdmin from "./src/Presentation/views/admin/IngresoPeatonaladmin";
import Citasadmin from "./src/Presentation/views/admin/citasadmin";
import Notificacionesadmin from "./src/Presentation/views/admin/Notificacioneadmin";
import ZonasComunesadmin from "./src/Presentation/views/admin/zonasadmin";

import Pagosadmin from "./src/Presentation/views/admin/Pagosadmin";
import Parqueaderoadmin from "./src/Presentation/views/admin/parqueaderoadmin";
import PerfilAdmin from "./src/Presentation/views/admin/perfiladmin";
import DetallePago from "./src/Presentation/views/admin/DetallePago";
import IngresoPeatonalguarda from "./src/Presentation/views/guarda/IngresoPeatonalaguarda";
import Notiresidente from "./src/Presentation/views/residente/notificacionesresi";
import DetallePagoresi from "./src/Presentation/views/residente/DetallePago";
import Anunciosguarda from "./src/Presentation/views/guarda/anunciosguarda";
import  NuevoPago from "./src/Presentation/views/admin/inpagoadmin";
import  Anunciosadmin  from "./src/Presentation/views/admin/anunciosadmin";
import  Anunciosresi from "./src/Presentation/views/residente/anunciosresidente";



export type RootStackParamList = {
    HomeScreen: undefined;
    RegisterScreen: undefined;
    ForgotPasswordScreen: undefined;
    GuardaLoadingScreen: undefined;
    ResidenteLoadingScreen: undefined;
    AdminLoadingScreen: undefined;
    registeradminloading: undefined;
    registerloadinresidente: undefined;
    guardaregistroloading: undefined;
    residentebienvenido: undefined;
    Guardabienvenido: undefined;
    adminbienvenido: undefined;
    ResidentePrincipal: undefined;
    GuardaPrincipal: undefined;
    AdminPrincipal: undefined;
    Parqueaderoresidente: undefined;
    ZonasComunes: undefined;
    Pagos: undefined;
    Perfil: undefined;
    Zonasguarda: undefined;
    Citas: undefined;
    Torres: undefined;
    Notificacionesguarda: undefined;
    IngresoPeatonal: undefined;
    Parqueaderoguarda: undefined;
    guardaperfil: undefined;
    Contactanosadmin: undefined;
    DatosUsuarios: undefined;
    Torresadmin: undefined;
    IngresoPeatonalAdmin: undefined;
    Citasadmin: undefined;
    Notificacionesadmin: undefined;
    ZonasComunesadmin: undefined;
    Pagosadmin: undefined;
    Parqueaderoadmin: undefined;
    PerfilAdmin: undefined;
    IngresoPeatonalguarda: undefined;
    Notiresidente: undefined;
    Anunciosguarda: undefined;
    NuevoPago:undefined;
    Anunciosadmin:undefined;
    Anunciosresi:undefined;


    DetallePago: {
        pago: {
            idPagos: number;
            pagoPor: string;
            cantidad: number;
            mediopago: string;
            apart: string;
            fechaPago: string;
            estado: 'Pendiente' | 'Pagado' | 'Vencido';
            referenciaPago?: string;
            PrimerNombre?: string;
            PrimerApellido?: string;
        }
    };

    DetallePagoresi: {
        pago: {
            idPagos: number;
            pagoPor: string;
            cantidad: number;
            mediopago: string;
            apart: string;
            fechaPago: string;
            estado: 'Pendiente' | 'Pagado' | 'Vencido';
            referenciaPago?: string;
            PrimerNombre?: string;
            PrimerApellido?: string;
        }
    };


};

const Stack = createNativeStackNavigator<RootStackParamList>();

const App = () => {
    const [isLoading, setIsLoading] = React.useState(true);

    React.useEffect(() => {
        const timer = setTimeout(() => {
            setIsLoading(false);
        }, 1000);

        return () => clearTimeout(timer);
    }, []);

    if (isLoading) {
        return <LoadingScreen />;
    }

    return (
        <AuthProvider>
            <NavigationContainer>
                <Stack.Navigator screenOptions={{ headerShown: false }}>

                    <Stack.Screen name="HomeScreen" component={HomeScreen} />
                    <Stack.Screen name="RegisterScreen" component={RegisterScreen} />
                    <Stack.Screen name="ForgotPasswordScreen" component={ForgotPasswordScreen} />
                    <Stack.Screen name="GuardaLoadingScreen" component={GuardaLoadingScreen} />
                    <Stack.Screen name="ResidenteLoadingScreen" component={ResidenteLoadingScreen} />
                    <Stack.Screen name="AdminLoadingScreen" component={AdminLoadingScreen} />
                    <Stack.Screen name="registeradminloading" component={registeradminloading} />
                    <Stack.Screen name="registerloadinresidente" component={registerloadinresidente} />
                    <Stack.Screen name="guardaregistroloading" component={guardaregistroloading} />
                    <Stack.Screen name="residentebienvenido" component={residentebienvenido} />
                    <Stack.Screen name="Guardabienvenido" component={Guardabienvenido} />
                    <Stack.Screen name="adminbienvenido" component={adminbienvenido} />
                    <Stack.Screen name="ResidentePrincipal" component={ResidentePrincipal} />
                    <Stack.Screen name="GuardaPrincipal" component={GuardaPrincipal} />
                    <Stack.Screen name="AdminPrincipal" component={AdminPrincipal} />
                    <Stack.Screen name="Parqueaderoresidente" component={Parqueaderoresidente} />
                    <Stack.Screen name="ZonasComunes" component={ZonasComunes} />
                    <Stack.Screen name="Pagos" component={Pagos} />
                    <Stack.Screen name="Perfil" component={Perfil} />
                    <Stack.Screen name="Zonasguarda" component={Zonasguarda} />
                    <Stack.Screen name="Citas" component={Citas} />
                    <Stack.Screen name="Torres" component={Torres} />
                    <Stack.Screen name='Notificacionesguarda' component={Notificacionesguarda} />
                    <Stack.Screen name='IngresoPeatonal' component={IngresoPeatonal} />
                    <Stack.Screen name='Parqueaderoguarda' component={Parqueaderoguarda} />
                    <Stack.Screen name='guardaperfil' component={guardaperfil} />
                    <Stack.Screen name='Contactanosadmin' component={Contactanosadmin} />
                    <Stack.Screen name='DatosUsuarios' component={DatosUsuarios} />
                    <Stack.Screen name='Torresadmin' component={Torresadmin} />
                    <Stack.Screen name='IngresoPeatonalAdmin' component={IngresoPeatonalAdmin} />
                    <Stack.Screen name='Citasadmin' component={Citasadmin} />
                    <Stack.Screen name='Notificacionesadmin' component={Notificacionesadmin} />
                    <Stack.Screen name='ZonasComunesadmin' component={ZonasComunesadmin} />
                    <Stack.Screen name='Pagosadmin' component={Pagosadmin} />
                    <Stack.Screen name='Parqueaderoadmin' component={Parqueaderoadmin} options={{ title: 'Administrar Parqueaderos' }} />
                    <Stack.Screen name='PerfilAdmin' component={PerfilAdmin} />
                    <Stack.Screen
                        name="DetallePago"
                        component={DetallePago}
                        options={{ title: 'Detalle de Pago' }}
                    />
                    <Stack.Screen name='IngresoPeatonalguarda' component={IngresoPeatonalguarda} />
                    <Stack.Screen name='Notiresidente' component={Notiresidente} />
                    <Stack.Screen
                        name="DetallePagoresi"
                        component={DetallePagoresi}
                        options={{ title: 'Detalle de Pago' }}
                    />
                    <Stack.Screen name='Anunciosguarda' component={Anunciosguarda} />
                    <Stack.Screen name='NuevoPago' component={NuevoPago} />
                    <Stack.Screen name='Anunciosadmin' component={Anunciosadmin} />
                    <Stack.Screen name='Anunciosresi' component={Anunciosresi} />



                </Stack.Navigator>
            </NavigationContainer>
        </AuthProvider>
    );
};

export default App;