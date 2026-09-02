import React from 'react';
import { Route } from 'react-router';
import { SwitchTransition } from 'react-transition-group';
import Fade from '@/components/elements/Fade';

const TransitionRouter: React.FC = ({ children }) => {
    return (
        <Route
            render={({ location }) => (
                <div className={'ptero-transition-router'}>
                    <SwitchTransition>
                        <Fade timeout={150} key={location.pathname + location.search} in appear unmountOnExit>
                            <section>{children}</section>
                        </Fade>
                    </SwitchTransition>
                </div>
            )}
        />
    );
};

export default TransitionRouter;
