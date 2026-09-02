import React, { memo } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { IconProp } from '@fortawesome/fontawesome-svg-core';
import isEqual from 'react-fast-compare';
import { Tile } from '@carbon/react';

interface Props {
    icon?: IconProp;
    title: string | React.ReactNode;
    className?: string;
    children: React.ReactNode;
}

const TitledGreyBox = ({ icon, title, children, className }: Props) => (
    <Tile className={`ptero-tile ${className || ''}`} style={{ padding: 0 }}>
        <div className={'ptero-tile__header'}>
            {typeof title === 'string' ? (
                <p>
                    {icon && <FontAwesomeIcon icon={icon} style={{ marginRight: '0.5rem' }} />}
                    {title}
                </p>
            ) : (
                title
            )}
        </div>
        <div className={'ptero-tile__body'}>{children}</div>
    </Tile>
);

export default memo(TitledGreyBox, isEqual);
