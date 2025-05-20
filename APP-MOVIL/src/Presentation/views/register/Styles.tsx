import { StyleSheet } from "react-native";


const RegisterStyles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#000',
    },
    imageBackground: {
        width: '100%',
        height: '100%',
        opacity: 0.7,
        position: 'absolute',
    },
    logoContainer: {
        position: 'absolute',
        alignSelf: 'center',
        top: '10%',
        alignItems: 'center',
    },
    logoImage: {
        width: 120,
        height: 120,
        borderRadius: 40, 
        borderWidth: 2,
        borderColor: '#045910',
    },
    logoText: {
        color: '#FFF',
        fontSize: 24,
        fontWeight: 'bold',
        marginTop: 10,
        textShadowColor: 'rgba(0, 0, 0, 0.75)',
        textShadowOffset: { width: 1, height: 1 },
        textShadowRadius: 10,
    },
    form: {
        width: '100%',
        height: '65%',
        backgroundColor: '#FFF',
        position: 'absolute',
        bottom: 0,
        borderTopLeftRadius: 30,
        borderTopRightRadius: 30,
        padding: 25,
    },
    formTitle: {
        fontSize: 24,
        fontWeight: 'bold',
        color: '#045910',
        textAlign: 'center',
        marginBottom: 20,
    },
    formInput: {
        flexDirection: 'row',
        alignItems: 'center',
        marginTop: 15,
    },
    formIcon: {
        width: 26,
        height: 26,
        marginRight: 11,
    },
    formTextInput: {
        flex: 2,
        borderBottomWidth: 1,
        borderBottomColor: '#AAAAAA',
        paddingVertical: 6,
    },
    buttonContainer: {
        marginTop: 30,
    },
    formFooter: {
        marginTop: 20,
        alignItems: 'center',
    },
    footerText: {
        fontSize: 14,
        color: '#045910',
    },
    footerLink: {
        fontSize: 14,
        color: '#0f6316',
        fontWeight: 'bold',
        marginTop: 5,
    },
    scrollContainer: {
        flexGrow: 1,
        justifyContent: 'center',
    },

    loadingContainer: {
        flexDirection: 'row',
        alignItems: 'center',
        padding: 10,
    },
    loadingText: {
        marginLeft: 10,
        color: 'green',
    },
    errorText: {
        color: 'red',
        fontSize: 10,
        marginTop: 4,
        marginLeft: 35
    },
});

export default RegisterStyles;