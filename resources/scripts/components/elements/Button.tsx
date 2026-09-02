import React from 'react';
import classNames from 'classnames';
import { Button as CarbonButton, InlineLoading } from '@carbon/react';

interface Props {
    isLoading?: boolean;
    size?: 'xsmall' | 'small' | 'large' | 'xlarge';
    color?: 'green' | 'red' | 'primary' | 'grey';
    isSecondary?: boolean;
}

const kindFor = (color?: Props['color'], isSecondary?: boolean) => {
    if (color === 'red') {
        return isSecondary ? 'danger--ghost' : 'danger';
    }
    if (color === 'grey') {
        return 'secondary';
    }
    if (isSecondary) {
        return 'tertiary';
    }
    return 'primary';
};

const sizeFor = (size?: Props['size']) => {
    if (size === 'xsmall') {
        return 'sm';
    }
    if (size === 'large' || size === 'xlarge') {
        return 'lg';
    }
    return 'md';
};

type ComponentProps = Omit<JSX.IntrinsicElements['button'], 'ref' | keyof Props> & Props;

const Button: React.FC<ComponentProps> = ({ children, isLoading, size, color, isSecondary, className, type, ...props }) => (
    <CarbonButton
        kind={kindFor(color, isSecondary)}
        size={sizeFor(size)}
        type={type}
        disabled={props.disabled || isLoading}
        className={classNames(className, size === 'xlarge' && 'w-full')}
        style={size === 'xlarge' ? { width: '100%' } : undefined}
        {...props}
    >
        {isLoading ? <InlineLoading description={'Loading'} /> : children}
    </CarbonButton>
);

type LinkProps = Omit<JSX.IntrinsicElements['a'], 'ref' | keyof Props> & Props;

const LinkButton: React.FC<LinkProps> = ({ size, color, isSecondary, className, children, ...props }) => (
        <CarbonButton
            as={'a'}
            kind={kindFor(color, isSecondary)}
            size={sizeFor(size)}
            className={className}
            style={size === 'xlarge' ? { width: '100%' } : undefined}
            {...(props as Record<string, unknown>)}
        >
        {children}
    </CarbonButton>
);

const ButtonStyle = CarbonButton;

export { LinkButton, ButtonStyle };
export default Button;
