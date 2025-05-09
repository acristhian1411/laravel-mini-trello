import React from 'react';
import { useForm, useState } from '@inertiajs/react';

export default function TokenForm() {
    const form = useForm({
        email: '',
        password: '',
    });
    let [error, setError] = useState({
        email: '',
        password: '',
    });
    const handleSubmit = (e) => {
        e.preventDefault();
        form.post(route('token.generate'), {
            onSuccess: () => {
                form.reset();
            },
            onError: (errors) => {
                setError(errors);
            },
        });
    };
    
    return (
        <form onSubmit={handleSubmit}>
            <div>
                <label htmlFor="email">Email</label>
                <input type="email" id="email" name="email" 
                    className="border border-gray-500 rounded"
                    value={form.email} onChange={(e) => form.email = e.target.value} />
                <p className="text-red-500">{error.email}</p>
            </div>
            <div>
                <label htmlFor="password">Password</label>
                <input type="password" id="password" name="password" 
                    className="border border-gray-500 rounded"
                    value={form.password} onChange={(e) => form.password = e.target.value} />
                <p className="text-red-500">{error.password}</p>
            </div>
            <button type="submit" className="bg-blue-500 text-white px-4 py-2 rounded">Generate Token</button>
        </form>
    );
}