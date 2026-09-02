import React from 'react';
import CSSTransition, { CSSTransitionProps } from 'react-transition-group/CSSTransition';

interface Props extends Omit<CSSTransitionProps, 'timeout' | 'classNames'> {
    timeout: number;
}

const Fade: React.FC<Props> = ({ timeout, children, ...props }) => {
    const child = React.Children.only(children) as React.ReactElement<{ className?: string; style?: React.CSSProperties }>;

    return (
        <CSSTransition timeout={timeout} classNames={'fade'} {...props}>
            {React.cloneElement(child, {
                className: child.props.className ? `${child.props.className} ptero-fade` : 'ptero-fade',
                style: { ...(child.props.style || {}), ['--ptero-fade-ms' as string]: `${timeout}ms` },
            })}
        </CSSTransition>
    );
};
Fade.displayName = 'Fade';

export default Fade;
