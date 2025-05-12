
import React, { createContext, useContext, useState, useEffect } from 'react';

// Define user roles
export type UserRole = 'admin' | 'committee' | 'observer';

// User type definition
export interface User {
  id: string;
  name: string;
  email: string;
  role: UserRole;
}

// Auth context type
interface AuthContextType {
  user: User | null;
  academicYear: string;
  isAuthenticated: boolean;
  login: (email: string, password: string, academicYear: string) => Promise<boolean>;
  logout: () => void;
  setAcademicYear: (year: string) => void;
}

// Create context
const AuthContext = createContext<AuthContextType | undefined>(undefined);

// Mock users for demo
const mockUsers: User[] = [
  { id: '1', name: 'Admin User', email: 'admin@madrasah.com', role: 'admin' },
  { id: '2', name: 'Committee User', email: 'committee@madrasah.com', role: 'committee' },
  { id: '3', name: 'Observer User', email: 'observer@madrasah.com', role: 'observer' },
];

// Default password for all users in the demo
const DEFAULT_PASSWORD = 'password123';

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [academicYear, setAcademicYear] = useState<string>('2024-2025');
  const [isAuthenticated, setIsAuthenticated] = useState<boolean>(false);

  useEffect(() => {
    // Check if user is stored in localStorage on component mount
    const storedUser = localStorage.getItem('madrasah_user');
    const storedYear = localStorage.getItem('madrasah_academic_year');
    
    if (storedUser) {
      setUser(JSON.parse(storedUser));
      setIsAuthenticated(true);
    }
    
    if (storedYear) {
      setAcademicYear(storedYear);
    }
  }, []);

  // Login function - in a real app, this would call an API
  const login = async (email: string, password: string, year: string): Promise<boolean> => {
    // Simplified mock authentication
    const foundUser = mockUsers.find(u => u.email === email);
    
    if (foundUser && password === DEFAULT_PASSWORD) {
      setUser(foundUser);
      setAcademicYear(year);
      setIsAuthenticated(true);
      
      // Store in localStorage for persistence
      localStorage.setItem('madrasah_user', JSON.stringify(foundUser));
      localStorage.setItem('madrasah_academic_year', year);
      
      return true;
    }
    
    return false;
  };

  // Logout function
  const logout = () => {
    setUser(null);
    setIsAuthenticated(false);
    localStorage.removeItem('madrasah_user');
  };

  // Update academic year
  const updateAcademicYear = (year: string) => {
    setAcademicYear(year);
    localStorage.setItem('madrasah_academic_year', year);
  };

  // Context value
  const contextValue: AuthContextType = {
    user,
    academicYear,
    isAuthenticated,
    login,
    logout,
    setAcademicYear: updateAcademicYear
  };

  return (
    <AuthContext.Provider value={contextValue}>
      {children}
    </AuthContext.Provider>
  );
};

// Custom hook for using auth context
export const useAuth = () => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};
