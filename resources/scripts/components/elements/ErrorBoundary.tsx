import React from 'react';
import Icon from '@/components/elements/Icon';
import { faExclamationTriangle } from '@fortawesome/free-solid-svg-icons';

interface State {
    hasError: boolean;
}

class ErrorBoundary extends React.Component<{}, State> {
    state: State = {
        hasError: false,
    };

    static getDerivedStateFromError() {
        return { hasError: true };
    }

    componentDidCatch(error: Error) {
        console.error(error);
    }

    render() {
        return this.state.hasError ? (
            <div className={'ptero-error-banner'}>
                <Icon icon={faExclamationTriangle} className={'ptero-icon'} />
                <p className={'ptero-muted'}>
                    An error was encountered by the application while rendering this view. Try refreshing the page.
                </p>
            </div>
        ) : (
            this.props.children
        );
    }
}

export default ErrorBoundary;
