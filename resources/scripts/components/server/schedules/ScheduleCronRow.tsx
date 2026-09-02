import React from 'react';
import { Schedule } from '@/api/server/schedules/getServerSchedules';
import classNames from 'classnames';

interface Props {
    cron: Schedule['cron'];
    className?: string;
}

const ScheduleCronRow = ({ cron, className }: Props) => (
    <div className={classNames('ptero-stack--row', className)} style={{ gap: '1rem' }}>
        <div className={'ptero-resource-row__meta'}>
            <p>{cron.minute}</p>
            <span className={'ptero-resource-row__label'}>Minute</span>
        </div>
        <div className={'ptero-resource-row__meta'}>
            <p>{cron.hour}</p>
            <span className={'ptero-resource-row__label'}>Hour</span>
        </div>
        <div className={'ptero-resource-row__meta'}>
            <p>{cron.dayOfMonth}</p>
            <span className={'ptero-resource-row__label'}>Day (Month)</span>
        </div>
        <div className={'ptero-resource-row__meta'}>
            <p>{cron.month}</p>
            <span className={'ptero-resource-row__label'}>Month</span>
        </div>
        <div className={'ptero-resource-row__meta'}>
            <p>{cron.dayOfWeek}</p>
            <span className={'ptero-resource-row__label'}>Day (Week)</span>
        </div>
    </div>
);

export default ScheduleCronRow;
