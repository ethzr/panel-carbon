import styled from 'styled-components/macro';

export default styled.div<{ $hoverable?: boolean }>`
    display: flex;
    align-items: center;
    text-decoration: none;
    overflow: hidden;
    padding: 1rem;
    background: var(--cds-layer);
    color: var(--cds-text-primary);
    border: 1px solid var(--cds-border-subtle-01);
    transition: background 110ms cubic-bezier(0.2, 0, 0.38, 0.9), border-color 110ms cubic-bezier(0.2, 0, 0.38, 0.9);

    ${(props) =>
        props.$hoverable !== false &&
        `
        &:hover {
            background: var(--cds-layer-hover);
            border-color: var(--cds-border-subtle-selected-01);
        }
    `};

    & .icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 4rem;
        padding: 0.75rem;
        background: var(--cds-layer-accent);
        color: var(--cds-icon-primary);
    }
`;
