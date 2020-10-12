<?php
namespace TypeRocket\Http\Middleware;

/**
 * Class OwnsPostOrCanEditPosts
 *
 * Validate that user can owns post or can edit posts and
 * if the user is not invalidate the response.
 *
 * @package TypeRocket\Http\Middleware
 */
class CanManageCategories extends Middleware
{
    public function handle() {

        if ( ! current_user_can( 'manage_categories' ) && ! $this->isHook() ) {
            tr_abort(401);
        }

        $this->next->handle();
    }
}