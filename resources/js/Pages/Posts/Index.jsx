import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import { Inertia } from '@inertiajs/inertia';

export default function Index() {
    const { posts } = usePage().props;
    const { deletePost } = usePage().props;

    const destroy = (id) => {
        Inertia.delete(`/posts/${id}`);
    };

    return (
        <div>
            <h2 align="center">
                Posts
            </h2>
            <table className="w-full table-auto">
                <thead className="bg-gray-50">
                    <tr>
                        <th className="px-4 py-2">Title</th>
                        <th className="px-4 py-2">
                            <Link
                                href="/posts/create"
                                className="text-blue-600 hover:text-blue-900 hover:underline"
                            >
                                Create
                            </Link>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {posts.map(post => (
                        <tr key={post.id}>
                            <td className="px-4 py-2 border-t">{post.title}</td>
                            <td className="px-4 py-2 border-t">
                                <Link
                                    href={`/posts/${post.id}/edit`}
                                    className="text-blue-600 hover:text-blue-900 hover:underline"
                                >
                                    Edit
                                </Link>
                            </td>
                            <td className="px-4 py-2 border-t">
                                <button
                                    type="button"
                                    onClick={() => destroy(post.id)}
                                    className="text-red-600 hover:text-red-900 hover:underline"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}
