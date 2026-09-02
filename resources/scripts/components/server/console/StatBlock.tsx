import React from 'react';
import Icon from '@/components/elements/Icon';
import { IconDefinition } from '@fortawesome/free-solid-svg-icons';
import classNames from 'classnames';
import useFitText from 'use-fit-text';
import CopyOnClick from '@/components/elements/CopyOnClick';

interface StatBlockProps {
    title: string;
    copyOnClick?: string;
    color?: string | undefined;
    icon: IconDefinition;
    children: React.ReactNode;
    className?: string;
}

export default ({ title, copyOnClick, icon, color, className, children }: StatBlockProps) => {
    const { fontSize, ref } = useFitText({ minFontSize: 8, maxFontSize: 16 });
    const tone = color === 'bg-red-500' ? 'is-error' : color === 'bg-yellow-500' ? 'is-warn' : undefined;

    return (
        <CopyOnClick text={copyOnClick}>
            <div className={classNames('ptero-stat', className)}>
                <div className={classNames('ptero-stat__bar', tone)} />
                <Icon icon={icon} />
                <div className={'ptero-stat__copy'}>
                    <p className={'ptero-stat__title'}>{title}</p>
                    <div ref={ref} className={'ptero-stat__value'} style={{ fontSize }}>
                        {children}
                    </div>
                </div>
            </div>
        </CopyOnClick>
    );
};
