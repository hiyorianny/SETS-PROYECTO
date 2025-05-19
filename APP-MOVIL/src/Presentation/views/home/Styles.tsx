import { StyleSheet } from "react-native";

const HomeStyles = StyleSheet.create({
    container: {
        flex: 2,
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
        top: '9%',
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
        fontSize: 28,
        fontWeight: 'bold',
        marginTop: 10,
        textShadowColor: 'rgba(15, 136, 31, 0.75)',
        textShadowOffset: { width: 1, height: 1 },
        textShadowRadius: 10,
    },
    form: {
        width: '100%',
        height: '55%',
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
        color: '#041c0d',
        textAlign: 'center',
        marginBottom: 20,
        
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
        color: '#041c0d',
        fontWeight: 'bold',
        marginTop: 5,
    },
    errorText: {
        color: 'red',
        fontSize: 12,
        marginTop: 5,
        marginLeft: 10,
        alignSelf: 'flex-start'
    },
    inputContainer: {
        marginBottom: 15,
        width: '100%'
    },
});

export default HomeStyles;