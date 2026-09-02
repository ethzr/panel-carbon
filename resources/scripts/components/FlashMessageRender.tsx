import React from 'react';
import MessageBox from '@/components/MessageBox';
import { useStoreState } from 'easy-peasy';

type Props = Readonly<{
    byKey?: string;
    className?: string;
}>;

const FlashMessageRender = ({ byKey, className }: Props) => {
    const flashes = useStoreState((state) =>
        state.flashes.items.filter((flash) => (byKey ? flash.key === byKey : true))
    );

    return flashes.length ? (
        <div className={className ? `ptero-stack ${className}` : 'ptero-stack'}>
            {flashes.map((flash) => (
                <MessageBox key={flash.id || flash.type + flash.message} type={flash.type} title={flash.title}>
                    {flash.message}
                </MessageBox>
            ))}
        </div>
    ) : null;
};

export default FlashMessageRender;
