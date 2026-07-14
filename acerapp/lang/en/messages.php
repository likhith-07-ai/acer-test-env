<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Messages
    |--------------------------------------------------------------------------
    |
    | Centralized messages for success and error notifications
    |
    */

    'success' => [
        'category' => [
            'created' => 'Category created successfully.',
            'updated' => 'Category updated successfully.',
            'deleted' => 'Category deleted successfully.',
        ],
        'document' => [
            'created' => 'Document uploaded successfully.',
            'updated' => 'Document updated successfully.',
            'deleted' => 'Document deleted successfully.',
        ],
        'research_category' => [
            'created' => 'Research category created successfully.',
            'updated' => 'Research category updated successfully.',
            'deleted' => 'Research category deleted successfully.',
        ],
        'research_article' => [
            'created' => 'Research article created successfully.',
            'updated' => 'Research article updated successfully.',
            'deleted' => 'Research article deleted successfully.',
            'approved' => 'Article approved successfully.',
            'rejected' => 'Article rejected successfully.',
            'published' => 'Article published successfully.',
        ],
        'policy' => [
            'created' => 'Policy created successfully.',
            'updated' => 'Policy updated successfully.',
            'deleted' => 'Policy deleted successfully.',
        ],
        'user' => [
            'created' => 'User created successfully.',
            'updated' => 'User updated successfully.',
            'deleted' => 'User deleted successfully.',
        ],
        'tag' => [
            'created' => 'Tag created successfully.',
            'updated' => 'Tag updated successfully.',
            'deleted' => 'Tag deleted successfully.',
        ],
        'contact' => [
            'sent' => 'Thank you for your message. We will get back to you soon.',
        ],
    ],

    'error' => [
        'general' => [
            'fetch' => 'An error occurred while fetching data. Please try again.',
            'create_form' => 'An error occurred while loading the form. Please try again.',
            'edit_form' => 'An error occurred while loading the form. Please try again.',
            'create' => 'An error occurred while creating the record. Please try again.',
            'update' => 'An error occurred while updating the record. Please try again.',
            'delete' => 'An error occurred while deleting the record. Please try again.',
            'load' => 'An error occurred while loading the data. Please try again.',
        ],
        'category' => [
            'has_documents' => 'Cannot delete category with associated documents.',
            'has_children' => 'Cannot delete category with sub-categories.',
            'has_sub_categories' => 'Cannot delete category with sub-categories. Please delete sub-categories first.',
        ],
        'research_category' => [
            'has_articles' => 'Cannot delete category with associated articles.',
            'has_children' => 'Cannot delete category with sub-categories. Please delete sub-categories first.',
        ],
        'document' => [
            'upload' => 'An error occurred while uploading the document. Please try again.',
            'update' => 'An error occurred while updating the document. Please try again.',
            'delete' => 'An error occurred while deleting the document. Please try again.',
        ],
        'research_article' => [
            'approve' => 'Only submitted articles can be approved.',
            'reject' => 'Only submitted articles can be rejected.',
            'publish' => 'Only approved articles can be published.',
        ],
        'user' => [
            'delete_self' => 'You cannot delete your own account.',
        ],
    ],

];
