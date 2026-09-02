import React from 'react';

const rows: [string, string][][] = [
    [
        ['*/5 * * * *', 'every 5 minutes'],
        ['0 */1 * * *', 'every hour'],
        ['0 8-12 * * *', 'hour range'],
        ['0 0 * * *', 'once a day'],
        ['0 0 * * MON', 'every Monday'],
    ],
    [
        ['*', 'any value'],
        [',', 'value list separator'],
        ['-', 'range values'],
        ['/', 'step values'],
    ],
];

export default () => {
    return (
        <div className={'ptero-split'}>
            <div className={'ptero-tile'}>
                <div className={'ptero-tile__header'}>Examples</div>
                <div className={'ptero-tile__body ptero-stack'}>
                    {rows[0].map(([expr, label]) => (
                        <div key={expr} className={'ptero-stack ptero-stack--row'}>
                            <code className={'ptero-code'}>{expr}</code>
                            <span className={'ptero-muted'}>{label}</span>
                        </div>
                    ))}
                </div>
            </div>
            <div className={'ptero-tile'}>
                <div className={'ptero-tile__header'}>Special Characters</div>
                <div className={'ptero-tile__body ptero-stack'}>
                    {rows[1].map(([expr, label]) => (
                        <div key={expr} className={'ptero-stack ptero-stack--row'}>
                            <code className={'ptero-code'}>{expr}</code>
                            <span className={'ptero-muted'}>{label}</span>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
};
