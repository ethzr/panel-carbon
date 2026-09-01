import { createGlobalStyle } from 'styled-components/macro';

export default createGlobalStyle`
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none !important;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield !important;
    }

    form {
        margin: 0;
    }
`;
