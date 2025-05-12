
import React, { useState } from 'react';
import { useAuth } from '@/contexts/AuthContext';
import { Button } from '@/components/ui/button';
import { CalendarRange, FileSpreadsheet, Home, LogOut, Settings, Users, DollarSign, BookOpen, BarChart, Plus } from 'lucide-react';
import { useNavigate, Outlet } from 'react-router-dom';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter, DialogDescription } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useToast } from '@/components/ui/use-toast';

const AppLayout: React.FC = () => {
  const { user, academicYear, setAcademicYear, logout } = useAuth();
  const navigate = useNavigate();
  const { toast } = useToast();
  const [isCollapsed, setIsCollapsed] = useState(false);
  const [openYearDialog, setOpenYearDialog] = useState(false);
  const [newYear, setNewYear] = useState('');
  const [availableYears, setAvailableYears] = useState(['2022-2023', '2023-2024', '2024-2025', '2025-2026']);

  const getMenuItems = () => {
    const allItems = [
      { title: 'Dasbor', url: '/dashboard', icon: Home, roles: ['admin', 'committee', 'observer'] },
      { title: 'Siswa', url: '/students', icon: Users, roles: ['admin', 'observer'] },
      { title: 'Jenis Pembayaran', url: '/payment-types', icon: Settings, roles: ['admin'] },
      { title: 'Pembayaran', url: '/payments', icon: DollarSign, roles: ['admin'] },
      { title: 'Dana Komite', url: '/committee-funds', icon: BookOpen, roles: ['admin', 'committee'] },
      { title: 'Laporan', url: '/reports', icon: BarChart, roles: ['admin', 'observer'] },
    ];
    
    if (!user) return [];
    
    return allItems.filter(item => item.roles.includes(user.role));
  };

  const handleLogout = () => {
    logout();
    toast({
      title: "Berhasil Keluar",
      description: "Anda telah berhasil keluar dari sistem.",
    });
    navigate('/login');
  };

  const handleYearChange = (value: string) => {
    setAcademicYear(value);
    toast({
      title: "Tahun Ajaran Diubah",
      description: `Tahun ajaran telah diperbarui ke ${value}.`,
    });
  };

  const handleAddYear = () => {
    if (newYear && !availableYears.includes(newYear)) {
      setAvailableYears([...availableYears, newYear]);
      setAcademicYear(newYear);
      setNewYear('');
      setOpenYearDialog(false);
      toast({
        title: "Tahun Ajaran Ditambahkan",
        description: `Tahun ajaran ${newYear} telah ditambahkan.`,
      });
    }
  };

  const menuItems = getMenuItems();

  const roleTranslations = {
    'admin': 'Admin',
    'committee': 'Komite',
    'observer': 'Pengamat'
  };

  return (
    <div className="flex h-screen bg-finance-light">
      {/* Sidebar */}
      <div className={`${isCollapsed ? 'w-16' : 'w-64'} transition-all duration-300 ease-in-out bg-white border-r shadow-sm`}>
        <div className="flex flex-col h-full">
          {/* Logo & app name */}
          <div className="flex items-center p-4 border-b bg-finance-primary text-white">
            {!isCollapsed && (
              <div className="flex items-center gap-2">
                <img 
                  src="/lovable-uploads/82497373-73f3-4a53-9afa-6e95566d9299.png" 
                  alt="Logo Kemenag" 
                  className="h-8 w-8"
                />
                <div className="flex flex-col">
                  <span className="text-sm font-medium">Keuangan Madrasah</span>
                  <span className="text-xs">Komite MAN 1 Tulungagung</span>
                </div>
              </div>
            )}
            {isCollapsed && (
              <img 
                src="/lovable-uploads/82497373-73f3-4a53-9afa-6e95566d9299.png" 
                alt="Logo Kemenag" 
                className="h-8 w-8 mx-auto"
              />
            )}
          </div>

          {/* Academic year selector */}
          {!isCollapsed && (
            <div className="p-4 bg-finance-light">
              <div className="flex items-center justify-between">
                <label className="text-sm font-medium text-gray-700 mb-1 block">Tahun Ajaran</label>
                <Button 
                  variant="ghost" 
                  size="sm" 
                  onClick={() => setOpenYearDialog(true)}
                >
                  <Plus className="h-4 w-4" />
                </Button>
              </div>
              <Select value={academicYear} onValueChange={handleYearChange}>
                <SelectTrigger className="bg-white">
                  <SelectValue placeholder={academicYear} />
                </SelectTrigger>
                <SelectContent>
                  {availableYears.map(year => (
                    <SelectItem key={year} value={year}>{year}</SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
          )}

          {/* Navigation */}
          <div className="flex-1 overflow-y-auto py-4">
            <nav className="px-2">
              <ul className="space-y-1">
                {menuItems.map((item) => (
                  <li key={item.title}>
                    <Button
                      variant="ghost"
                      className={`w-full justify-start ${isCollapsed ? 'px-2' : 'px-4'} py-2 hover:bg-finance-light hover:text-finance-primary`}
                      onClick={() => navigate(item.url)}
                    >
                      <item.icon className={`h-5 w-5 ${isCollapsed ? 'mx-auto' : 'mr-3'}`} />
                      {!isCollapsed && <span>{item.title}</span>}
                    </Button>
                  </li>
                ))}
              </ul>
            </nav>
          </div>

          {/* User profile & logout */}
          <div className="p-4 border-t bg-finance-light">
            {!isCollapsed && (
              <>
                <div className="flex items-center space-x-3 mb-3">
                  <Avatar>
                    <AvatarFallback className="bg-finance-primary text-white">
                      {user?.name.substring(0, 2).toUpperCase()}
                    </AvatarFallback>
                  </Avatar>
                  <div>
                    <p className="text-sm font-medium">{user?.name}</p>
                    <p className="text-xs text-gray-500 capitalize">
                      {user?.role ? roleTranslations[user.role as keyof typeof roleTranslations] : ''}
                    </p>
                  </div>
                </div>
                <Button variant="outline" className="w-full justify-start" onClick={handleLogout}>
                  <LogOut className="h-4 w-4 mr-2" />
                  Keluar
                </Button>
              </>
            )}
            {isCollapsed && (
              <Button size="icon" variant="outline" className="w-8 h-8 mx-auto" onClick={handleLogout}>
                <LogOut className="h-4 w-4" />
              </Button>
            )}
          </div>
        </div>
      </div>

      {/* Main content */}
      <div className="flex-1 flex flex-col overflow-hidden">
        {/* Header */}
        <header className="h-16 border-b bg-white flex items-center justify-between px-6 shadow-sm">
          <Button 
            variant="ghost" 
            size="icon" 
            className="mr-4" 
            onClick={() => setIsCollapsed(!isCollapsed)}
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="lucide lucide-menu">
              <line x1="4" x2="20" y1="12" y2="12"/>
              <line x1="4" x2="20" y1="6" y2="6"/>
              <line x1="4" x2="20" y1="18" y2="18"/>
            </svg>
          </Button>
          <div className="flex items-center space-x-4">
            {isCollapsed && (
              <Select value={academicYear} onValueChange={handleYearChange}>
                <SelectTrigger className="w-40">
                  <CalendarRange className="h-4 w-4 mr-2" />
                  <SelectValue placeholder={academicYear} />
                </SelectTrigger>
                <SelectContent>
                  {availableYears.map(year => (
                    <SelectItem key={year} value={year}>{year}</SelectItem>
                  ))}
                </SelectContent>
              </Select>
            )}
          </div>
        </header>

        {/* Page content */}
        <main className="flex-1 overflow-y-auto bg-finance-light p-6">
          <Outlet />
        </main>
      </div>

      {/* Add Year Dialog */}
      <Dialog open={openYearDialog} onOpenChange={setOpenYearDialog}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Tambah Tahun Ajaran</DialogTitle>
            <DialogDescription>
              Masukkan tahun ajaran baru dengan format YYYY-YYYY.
            </DialogDescription>
          </DialogHeader>
          <div className="py-4">
            <Label htmlFor="new-year">Tahun Ajaran</Label>
            <Input 
              id="new-year"
              placeholder="Contoh: 2026-2027"
              value={newYear}
              onChange={(e) => setNewYear(e.target.value)}
            />
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setOpenYearDialog(false)}>Batal</Button>
            <Button onClick={handleAddYear}>Tambah</Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  );
};

export default AppLayout;
