import React, { memo, useEffect, useRef, useState } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEthernet, faHdd, faMemory, faMicrochip, faServer } from '@fortawesome/free-solid-svg-icons';
import { Link } from 'react-router-dom';
import { Server } from '@/api/server/getServer';
import getServerResourceUsage, { ServerStats } from '@/api/server/getServerResourceUsage';
import { bytesToString, ip, mbToBytes } from '@/lib/formatters';
import Spinner from '@/components/elements/Spinner';
import classNames from 'classnames';
import isEqual from 'react-fast-compare';

const isAlarmState = (current: number, limit: number): boolean => limit > 0 && current / (limit * 1024 * 1024) >= 0.9;

type Timer = ReturnType<typeof setInterval>;

export default ({ server, className }: { server: Server; className?: string }) => {
    const interval = useRef<Timer>(null) as React.MutableRefObject<Timer>;
    const [isSuspended, setIsSuspended] = useState(server.status === 'suspended');
    const [stats, setStats] = useState<ServerStats | null>(null);

    const getStats = () =>
        getServerResourceUsage(server.uuid)
            .then((data) => setStats(data))
            .catch((error) => console.error(error));

    useEffect(() => {
        setIsSuspended(stats?.isSuspended || server.status === 'suspended');
    }, [stats?.isSuspended, server.status]);

    useEffect(() => {
        if (isSuspended || server.isNodeUnderMaintenance) return;

        getStats().then(() => {
            interval.current = setInterval(() => getStats(), 30000);
        });

        return () => {
            interval.current && clearInterval(interval.current);
        };
    }, [isSuspended, server.isNodeUnderMaintenance]);

    const alarms = { cpu: false, memory: false, disk: false };
    if (stats) {
        alarms.cpu = server.limits.cpu === 0 ? false : stats.cpuUsagePercent >= server.limits.cpu * 0.9;
        alarms.memory = isAlarmState(stats.memoryUsageInBytes, server.limits.memory);
        alarms.disk = server.limits.disk === 0 ? false : isAlarmState(stats.diskUsageInBytes, server.limits.disk);
    }

    const diskLimit = server.limits.disk !== 0 ? bytesToString(mbToBytes(server.limits.disk)) : 'Unlimited';
    const memoryLimit = server.limits.memory !== 0 ? bytesToString(mbToBytes(server.limits.memory)) : 'Unlimited';
    const cpuLimit = server.limits.cpu !== 0 ? server.limits.cpu + ' %' : 'Unlimited';
    const status = stats?.status;

    return (
        <Link
            to={`/server/${server.id}`}
            className={classNames(
                'cds--tile cds--tile--clickable ptero-server-row',
                status && `ptero-server-row--${status}`,
                className
            )}
        >
            <div>
                <p className={'cds--productive-heading-02'}>{server.name}</p>
                {!!server.description && <p className={'ptero-muted'}>{server.description}</p>}
            </div>
            <div className={'ptero-server-row__stat'}>
                <p className={'ptero-server-row__stat-value'}>
                    <FontAwesomeIcon icon={faEthernet} style={{ marginRight: '0.5rem' }} />
                    {server.allocations
                        .filter((alloc) => alloc.isDefault)
                        .map((allocation) => (
                            <React.Fragment key={allocation.ip + allocation.port.toString()}>
                                {allocation.alias || ip(allocation.ip)}:{allocation.port}
                            </React.Fragment>
                        ))}
                </p>
            </div>
            {!stats || isSuspended || server.isNodeUnderMaintenance ? (
                <div style={{ gridColumn: 'span 3' }}>
                    {isSuspended ? (
                        <span className={'cds--tag cds--tag--red cds--tag--sm'}>
                            {server.status === 'suspended' ? 'Suspended' : 'Connection Error'}
                        </span>
                    ) : server.isNodeUnderMaintenance ? (
                        <span className={'cds--tag cds--tag--purple cds--tag--sm'}>Under Maintenance</span>
                    ) : server.isTransferring || server.status ? (
                        <span className={'cds--tag cds--tag--gray cds--tag--sm'}>
                            {server.isTransferring
                                ? 'Transferring'
                                : server.status === 'installing'
                                ? 'Installing'
                                : server.status === 'restoring_backup'
                                ? 'Restoring Backup'
                                : 'Unavailable'}
                        </span>
                    ) : (
                        <Spinner size={'small'} />
                    )}
                </div>
            ) : (
                <>
                    <div className={'ptero-server-row__stat'}>
                        <p className={classNames('ptero-server-row__stat-value', alarms.cpu && 'is-alarm')}>
                            <FontAwesomeIcon icon={faMicrochip} style={{ marginRight: '0.35rem' }} />
                            {stats.cpuUsagePercent.toFixed(2)} %
                        </p>
                        <p className={'ptero-server-row__stat-limit'}>of {cpuLimit}</p>
                    </div>
                    <div className={'ptero-server-row__stat'}>
                        <p className={classNames('ptero-server-row__stat-value', alarms.memory && 'is-alarm')}>
                            <FontAwesomeIcon icon={faMemory} style={{ marginRight: '0.35rem' }} />
                            {bytesToString(stats.memoryUsageInBytes)}
                        </p>
                        <p className={'ptero-server-row__stat-limit'}>of {memoryLimit}</p>
                    </div>
                    <div className={'ptero-server-row__stat'}>
                        <p className={classNames('ptero-server-row__stat-value', alarms.disk && 'is-alarm')}>
                            <FontAwesomeIcon icon={faHdd} style={{ marginRight: '0.35rem' }} />
                            {bytesToString(stats.diskUsageInBytes)}
                        </p>
                        <p className={'ptero-server-row__stat-limit'}>of {diskLimit}</p>
                    </div>
                </>
            )}
        </Link>
    );
};
