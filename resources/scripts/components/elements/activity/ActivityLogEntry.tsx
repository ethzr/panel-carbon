import React from 'react';
import { Link } from 'react-router-dom';
import Tooltip from '@/components/elements/tooltip/Tooltip';
import Translate from '@/components/elements/Translate';
import { format, formatDistanceToNowStrict } from 'date-fns';
import { ActivityLog } from '@definitions/user';
import ActivityLogMetaButton from '@/components/elements/activity/ActivityLogMetaButton';
import Avatar from '@/components/Avatar';
import useLocationHash from '@/plugins/useLocationHash';
import { getObjectKeys, isObject } from '@/lib/objects';

interface Props {
    activity: ActivityLog;
    children?: React.ReactNode;
}

function wrapProperties(value: unknown): any {
    if (value === null || typeof value === 'string' || typeof value === 'number') {
        return `<strong>${String(value)}</strong>`;
    }

    if (isObject(value)) {
        return getObjectKeys(value).reduce((obj, key) => {
            if (key === 'count' || (typeof key === 'string' && key.endsWith('_count'))) {
                return { ...obj, [key]: value[key] };
            }
            return { ...obj, [key]: wrapProperties(value[key]) };
        }, {} as Record<string, unknown>);
    }

    if (Array.isArray(value)) {
        return value.map(wrapProperties);
    }

    return value;
}

export default ({ activity, children }: Props) => {
    const { pathTo } = useLocationHash();
    const actor = activity.relationships.actor;
    const properties = wrapProperties(activity.properties);

    return (
        <div className={'ptero-activity__row'}>
            <div className={'ptero-activity__avatar'}>
                <Avatar name={actor?.uuid || 'system'} />
            </div>
            <div>
                <p>
                    <Tooltip placement={'top'} content={actor?.email || 'System User'}>
                        <span>{actor?.username || 'System'}</span>
                    </Tooltip>
                    <span className={'ptero-muted'}>&nbsp;—&nbsp;</span>
                    <Link to={`#${pathTo({ event: activity.event })}`}>{activity.event}</Link>
                    {activity.isApi && <span className={'cds--tag cds--tag--sm cds--tag--blue'}>API</span>}
                    {activity.event.startsWith('server:sftp.') && (
                        <span className={'cds--tag cds--tag--sm cds--tag--gray'}>SFTP</span>
                    )}
                    {children}
                </p>
                <p className={'ptero-muted'}>
                    <Translate ns={'activity'} values={properties} i18nKey={activity.event.replace(':', '.')} />
                </p>
                <p className={'ptero-muted'}>
                    {activity.ip && <span>{activity.ip} | </span>}
                    <Tooltip placement={'right'} content={format(activity.timestamp, 'MMM do, yyyy H:mm:ss')}>
                        <span>{formatDistanceToNowStrict(activity.timestamp, { addSuffix: true })}</span>
                    </Tooltip>
                </p>
            </div>
            {activity.hasAdditionalMetadata && <ActivityLogMetaButton meta={activity.properties} />}
        </div>
    );
};
