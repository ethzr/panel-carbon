import styled from 'styled-components/macro';

const Label = styled.label<{ isLight?: boolean }>`
    display: inline-block;
    margin-bottom: 0.5rem;
    color: ${(props) => (props.isLight ? 'var(--cds-text-secondary)' : 'var(--cds-text-secondary)')};
    font-size: 0.75rem;
    font-weight: 400;
    line-height: 1rem;
    letter-spacing: 0.32px;
`;

export default Label;
