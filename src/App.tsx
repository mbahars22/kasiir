
import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import { useState, useEffect } from "react";
import { AuthProvider, useAuth } from "@/contexts/AuthContext";
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";

// Pages
import Login from "./pages/Login";
import Dashboard from "./pages/Dashboard";
import Students from "./pages/Students";
import PaymentTypes from "./pages/PaymentTypes";
import Payments from "./pages/Payments";
import CommitteeFunds from "./pages/CommitteeFunds";
import Reports from "./pages/Reports";
import NotFound from "./pages/NotFound";

// Master pages
import MasterBatch from "./pages/MasterBatch";
import MasterUsers from "./pages/MasterUsers";
import MasterSchoolProfile from "./pages/MasterSchoolProfile";

// Layouts
import AppLayout from "./components/layouts/AppLayout";

// Create a new instance of QueryClient with specific configuration to avoid React 18 effects issues
const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      staleTime: 5 * 60 * 1000, // 5 minutes
      retry: 1,
    },
  },
});

// Dialog untuk menambah tahun ajaran
const AcademicYearDialog = ({ isOpen, onClose, onSubmit }: { isOpen: boolean; onClose: () => void; onSubmit: (year: string) => void }) => {
  const [year, setYear] = useState('');

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (year.trim()) {
      onSubmit(year);
      setYear('');
      onClose();
    }
  };

  return (
    <Dialog open={isOpen} onOpenChange={onClose}>
      <DialogContent className="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle>Tambah Tahun Ajaran Baru</DialogTitle>
          <DialogDescription>
            Masukkan tahun ajaran baru dalam format 'YYYY/YYYY'.
          </DialogDescription>
        </DialogHeader>
        <form onSubmit={handleSubmit} className="space-y-4">
          <div className="space-y-2">
            <Label htmlFor="year">Tahun Ajaran</Label>
            <Input
              id="year"
              value={year}
              onChange={(e) => setYear(e.target.value)}
              placeholder="Contoh: 2023/2024"
            />
          </div>
          <DialogFooter>
            <Button type="button" variant="outline" onClick={onClose}>
              Batal
            </Button>
            <Button type="submit">Tambah</Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>
  );
};

// Protected route component
const ProtectedRoute = ({ children, allowedRoles = [] }: { children: React.ReactNode, allowedRoles?: string[] }) => {
  const { isAuthenticated, user } = useAuth();

  if (!isAuthenticated) {
    return <Navigate to="/login" replace />;
  }

  if (allowedRoles.length > 0 && user && !allowedRoles.includes(user.role)) {
    return <Navigate to="/dashboard" replace />;
  }

  return <>{children}</>;
};

// App routes component (used inside AuthProvider)
const AppRoutes = () => {
  const { isAuthenticated, academicYear, setAcademicYear } = useAuth();
  const [isYearDialogOpen, setIsYearDialogOpen] = useState(false);
  const [academicYears, setAcademicYears] = useState<string[]>(['2022/2023', '2023/2024']);
  
  // Tambah tahun ajaran baru
  const handleAddAcademicYear = (year: string) => {
    if (!academicYears.includes(year)) {
      setAcademicYears([...academicYears, year]);
      setAcademicYear(year);
    }
  };
  
  useEffect(() => {
    // Jika belum ada tahun ajaran yang dipilih, pilih yang terbaru
    if (!academicYear && academicYears.length > 0) {
      setAcademicYear(academicYears[academicYears.length - 1]);
    }
  }, []);
  
  return (
    <>
      <Routes>
        <Route path="/login" element={!isAuthenticated ? <Login /> : <Navigate to="/dashboard" replace />} />
        
        {/* Protected routes inside AppLayout */}
        <Route path="/" element={
          <ProtectedRoute>
            <AppLayout />
          </ProtectedRoute>
        }>
          <Route index element={<Navigate to="/dashboard" replace />} />
          <Route path="dashboard" element={<Dashboard />} />
          <Route path="students" element={
            <ProtectedRoute allowedRoles={['admin', 'observer']}>
              <Students />
            </ProtectedRoute>
          } />
          <Route path="payment-types" element={
            <ProtectedRoute allowedRoles={['admin']}>
              <PaymentTypes />
            </ProtectedRoute>
          } />
          <Route path="payments" element={
            <ProtectedRoute allowedRoles={['admin']}>
              <Payments />
            </ProtectedRoute>
          } />
          <Route path="committee-funds" element={
            <ProtectedRoute allowedRoles={['admin', 'committee']}>
              <CommitteeFunds />
            </ProtectedRoute>
          } />
          <Route path="reports" element={
            <ProtectedRoute allowedRoles={['admin', 'observer']}>
              <Reports />
            </ProtectedRoute>
          } />
          
          {/* Master Menu Routes */}
          <Route path="master/batch" element={
            <ProtectedRoute allowedRoles={['admin']}>
              <MasterBatch />
            </ProtectedRoute>
          } />
          <Route path="master/payment-types" element={
            <ProtectedRoute allowedRoles={['admin']}>
              <PaymentTypes />
            </ProtectedRoute>
          } />
          <Route path="master/users" element={
            <ProtectedRoute allowedRoles={['admin']}>
              <MasterUsers />
            </ProtectedRoute>
          } />
          <Route path="master/school-profile" element={
            <ProtectedRoute allowedRoles={['admin']}>
              <MasterSchoolProfile />
            </ProtectedRoute>
          } />
          
        </Route>
        
        {/* Catch-all route */}
        <Route path="*" element={<NotFound />} />
      </Routes>
      
      <AcademicYearDialog 
        isOpen={isYearDialogOpen}
        onClose={() => setIsYearDialogOpen(false)}
        onSubmit={handleAddAcademicYear}
      />
    </>
  );
};

const App = () => (
  <BrowserRouter>
    <AuthProvider>
      <QueryClientProvider client={queryClient}>
        <TooltipProvider>
          <Toaster />
          <Sonner />
          <AppRoutes />
        </TooltipProvider>
      </QueryClientProvider>
    </AuthProvider>
  </BrowserRouter>
);

export default App;
