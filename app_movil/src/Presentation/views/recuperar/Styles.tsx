import { StyleSheet } from "react-native";

const ForgotPasswordStyles = StyleSheet.create({
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
        height: '50%',
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
        width: 25,
        height: 25,
        marginRight: 10,
    },
    formTextInput: {
        flex: 1,
        borderBottomWidth: 1,
        borderBottomColor: '#AAAAAA',
        paddingVertical: 5,
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
        color: '#045910',
        fontWeight: 'bold',
        marginTop: 5,
    },
});

export default ForgotPasswordStyles;