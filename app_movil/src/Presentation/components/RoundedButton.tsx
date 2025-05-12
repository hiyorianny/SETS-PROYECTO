import React from 'react'
import { MyColors } from '../theme/AppTheme';
import { TouchableOpacity, Text, StyleSheet } from 'react-native'
interface Props {
    text: string;
    onPress: () => void,
}
export const RoundedButton = ({ text, onPress }: Props) => {
    return (
        <TouchableOpacity
            style={styles.RoundedButton}
            onPress={() => onPress()}
        >
            <Text style={styles.textButton} >{text}</Text>
        </TouchableOpacity>
    )
}
const styles = StyleSheet.create({
    RoundedButton: {
        width: '100%',
        height: 50,
        backgroundColor: MyColors.primary,
        alignItems: 'center',
        justifyContent: 'center',
        borderRadius: 15,
    },
    textButton: {
        color: 'white',
    }
});