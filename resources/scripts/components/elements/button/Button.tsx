import React, { forwardRef } from 'react';
import { Button as CarbonButton } from '@carbon/react';
import { ButtonProps, Options } from '@/components/elements/button/types';

const sizeFor = (size?: ButtonProps['size']) => {
    if (size === Options.Size.Small) {
        return 'sm';
    }
    if (size === Options.Size.Large) {
        return 'lg';
    }
    return 'md';
};

const mapButton = (
    kind: 'primary' | 'secondary' | 'ghost' | 'danger',
    { children, shape, size, variant: _variant, className, type, ...rest }: ButtonProps,
    ref: React.Ref<HTMLButtonElement>
) => {
    const iconOnly = shape === Options.Shape.IconSquare;

    return (
        <CarbonButton
            ref={ref}
            kind={kind}
            size={sizeFor(size)}
            type={type}
            hasIconOnly={iconOnly || undefined}
            iconDescription={iconOnly ? rest['aria-label'] || 'Action' : undefined}
            className={className}
            {...rest}
        >
            {children}
        </CarbonButton>
    );
};

const Button = forwardRef<HTMLButtonElement, ButtonProps>((props, ref) =>
    mapButton(props.variant === Options.Variant.Secondary ? 'secondary' : 'primary', props, ref)
);

const TextButton = forwardRef<HTMLButtonElement, ButtonProps>((props, ref) => mapButton('ghost', props, ref));

const DangerButton = forwardRef<HTMLButtonElement, ButtonProps>((props, ref) => mapButton('danger', props, ref));

const _Button = Object.assign(Button, {
    Sizes: Options.Size,
    Shapes: Options.Shape,
    Variants: Options.Variant,
    Text: TextButton,
    Danger: DangerButton,
});

Button.displayName = 'Button';
TextButton.displayName = 'TextButton';
DangerButton.displayName = 'DangerButton';

export default _Button;
