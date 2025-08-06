<?php

namespace App\Models;

/**
 * User Model
 * 
 * Handles user data and authentication
 */
class User extends BaseModel
{
    protected $table = 'users';
    
    protected $fillable = [
        'username',
        'email', 
        'password_hash',
        'full_name',
        'phone',
        'region',
        'role',
        'is_active',
        'email_verified',
        'verification_token',
        'reset_token',
        'reset_token_expires',
        'last_login'
    ];
    
    protected $hidden = [
        'password_hash',
        'verification_token',
        'reset_token'
    ];
    
    /**
     * Create new user
     */
    public function createUser($data)
    {
        // Hash password
        if (isset($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
        }
        
        // Generate verification token
        $data['verification_token'] = generateRandomString(32);
        
        // Set defaults
        $data['role'] = $data['role'] ?? 'user';
        $data['is_active'] = $data['is_active'] ?? true;
        $data['email_verified'] = $data['email_verified'] ?? false;
        
        return $this->create($data);
    }
    
    /**
     * Authenticate user
     */
    public function authenticate($email, $password)
    {
        $user = $this->findBy('email', $email);
        
        if (!$user) {
            return false;
        }
        
        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }
        
        if (!$user['is_active']) {
            return false;
        }
        
        // Update last login
        $this->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
        
        return $user;
    }
    
    /**
     * Update password
     */
    public function updatePassword($userId, $newPassword)
    {
        return $this->update($userId, [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
            'reset_token' => null,
            'reset_token_expires' => null
        ]);
    }
    
    /**
     * Generate password reset token
     */
    public function generateResetToken($email)
    {
        $user = $this->findBy('email', $email);
        
        if (!$user) {
            return false;
        }
        
        $token = generateRandomString(32);
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $this->update($user['id'], [
            'reset_token' => $token,
            'reset_token_expires' => $expires
        ]);
        
        return $token;
    }
    
    /**
     * Verify reset token
     */
    public function verifyResetToken($token)
    {
        $stmt = $this->db()->prepare("
            SELECT * FROM {$this->table} 
            WHERE reset_token = ? 
            AND reset_token_expires > NOW()
            LIMIT 1
        ");
        
        $stmt->execute([$token]);
        return $stmt->fetch();
    }
    
    /**
     * Verify email
     */
    public function verifyEmail($token)
    {
        $user = $this->findBy('verification_token', $token);
        
        if (!$user) {
            return false;
        }
        
        return $this->update($user['id'], [
            'email_verified' => true,
            'verification_token' => null
        ]);
    }
    
    /**
     * Get user statistics
     */
    public function getUserStats($userId)
    {
        $stats = [];
        
        // Get fatwa count
        $stmt = $this->db()->prepare("SELECT COUNT(*) as total FROM fatwas WHERE user_id = ?");
        $stmt->execute([$userId]);
        $stats['fatwas'] = $stmt->fetch()['total'];
        
        // Get donation total
        $stmt = $this->db()->prepare("
            SELECT COUNT(*) as count, SUM(amount) as total 
            FROM donations 
            WHERE user_id = ? AND payment_status = 'completed'
        ");
        $stmt->execute([$userId]);
        $donation = $stmt->fetch();
        $stats['donations_count'] = $donation['count'];
        $stats['donations_total'] = $donation['total'] ?? 0;
        
        // Get zakat calculations
        $stmt = $this->db()->prepare("SELECT COUNT(*) as total FROM zakat_calculations WHERE user_id = ?");
        $stmt->execute([$userId]);
        $stats['zakat_calculations'] = $stmt->fetch()['total'];
        
        return $stats;
    }
    
    /**
     * Get users by role
     */
    public function getUsersByRole($role)
    {
        return $this->where(['role' => $role, 'is_active' => true], 'full_name ASC');
    }
    
    /**
     * Search users
     */
    public function searchUsers($query, $role = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE (username LIKE ? OR email LIKE ? OR full_name LIKE ?)";
        $params = ["%{$query}%", "%{$query}%", "%{$query}%"];
        
        if ($role) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }
        
        $sql .= " ORDER BY full_name ASC";
        
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        
        return $this->hideFields($stmt->fetchAll());
    }
    
    /**
     * Check if username exists
     */
    public function usernameExists($username, $excludeId = null)
    {
        return $this->exists('username', $username, $excludeId);
    }
    
    /**
     * Check if email exists
     */
    public function emailExists($email, $excludeId = null)
    {
        return $this->exists('email', $email, $excludeId);
    }
}