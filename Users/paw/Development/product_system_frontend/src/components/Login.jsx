const handleLogin = async (e) => {
        e.preventDefault();
        try {
            const response = await axios.post('http://localhost:8000/api/login', {
                email,
                password
            });

            // Parse the response if it's a string
            const data = typeof response.data === 'string' 
                ? JSON.parse(response.data.substring(response.data.lastIndexOf('{')))
                : response.data;

            if (data.token) {
                localStorage.setItem('token', data.token);
                localStorage.setItem('timestamp', data.timestamp);
                localStorage.setItem('email', data.email);

                // Debug log
                console.log('Stored auth data:', {
                    token: localStorage.getItem('token'),
                    timestamp: localStorage.getItem('timestamp'),
                    email: localStorage.getItem('email')
                });

                navigate('/products');
            }
        } catch (err) {
            console.error('Login error:', err);
            setError('Login failed. Please check your credentials.');
        }
    };