import React from 'react';
import classNames from 'classnames';
import { Link } from 'react-router-dom';
import { Tile } from '@carbon/react';

type Props = {
    as?: React.ElementType;
    to?: string;
    href?: string;
    $hoverable?: boolean;
    className?: string;
    children?: React.ReactNode;
} & React.HTMLAttributes<HTMLElement>;

const GreyRowBox = ({ as, to, href, $hoverable = true, className, children, ...props }: Props) => {
    const classes = classNames(
        'ptero-resource-row',
        $hoverable !== false && 'ptero-resource-row--interactive',
        className
    );

    if (as === Link || to) {
        return (
            <Link
                to={to || href || '#'}
                className={classNames(classes, 'cds--tile cds--tile--clickable')}
                {...(props as React.AnchorHTMLAttributes<HTMLAnchorElement>)}
            >
                {children}
            </Link>
        );
    }

    if (as === 'a' || href) {
        return (
            <a href={href} className={classNames(classes, 'cds--tile cds--tile--clickable')} {...props}>
                {children}
            </a>
        );
    }

    return (
        <Tile className={classes} {...(props as React.HTMLAttributes<HTMLDivElement>)}>
            {children}
        </Tile>
    );
};

export default GreyRowBox;
